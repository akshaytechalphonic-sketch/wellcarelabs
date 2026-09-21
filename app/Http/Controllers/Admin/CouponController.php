<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ManageCouponRequest;
use App\Models\Coupon;
use App\Models\User;
use App\Notifications\CouponStatusNotification;
use Carbon\Carbon;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::query()
            ->when(request('q'), fn($q) =>
                $q->where('code', 'like', '%' . request('q') . '%')
            )
            ->when(request('status') === 'live', fn($q) => $q->live())
            ->when(request('status') === 'scheduled', fn($q) => $q->scheduled())
            ->when(request('status') === 'expired', fn($q) => $q->expired())
            ->when(request('status') === 'inactive', fn($q) => $q->inactive())
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupons.create', ['coupon' => new Coupon()]);
    }

    public function store(ManageCouponRequest $request)
    {
        // Use validatedData() from the request which returns parsed datetimes (Carbon), booleans, and nulls
        $data = $request->validatedData();

        // If you want to ensure seconds are zeroed (optional)
        if (!empty($data['starts_at'])) $data['starts_at']->second(0);
        if (!empty($data['expires_at'])) $data['expires_at']->second(0);

        $coupon = Coupon::create($data);

        // 🔔 If this newly created coupon is already "live", notify admins
        if ($this->isLive($coupon)) {
            $this->notifyAdminsCouponStatus($coupon, 'live');
        }

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon created.');
    }

    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.create', compact('coupon'));
    }

    public function update(ManageCouponRequest $request, Coupon $coupon)
    {
        $data = $request->validatedData();

        if (!empty($data['starts_at'])) $data['starts_at']->second(0);
        if (!empty($data['expires_at'])) $data['expires_at']->second(0);

        // 🔔 detect transitions
        $wasLive = $this->isLive($coupon);
        $wasActive = (bool) $coupon->is_active;

        $coupon->update($data);

        $isLive = $this->isLive($coupon);
        $isActive = (bool) $coupon->is_active;

        // Case 1: was not live, now live -> send "live" notification
        if (!$wasLive && $isLive) {
            $this->notifyAdminsCouponStatus($coupon, 'live');
        }

        // Case 2: was active, now inactive -> send "inactive" notification
        if ($wasActive && !$isActive) {
            $this->notifyAdminsCouponStatus($coupon, 'inactive');
        }

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon updated.');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return back()->with('success', 'Coupon deleted.');
    }

    /**
     * This `clean()` method was part of your original controller.
     * The current controller now relies on ManageCouponRequest::validatedData()
     * for the majority of normalization, so `clean()` is left here in case
     * you still want to call it elsewhere. Feel free to remove it if unused.
     */
    private function clean(ManageCouponRequest $request): array
    {
        $data = $request->validated();

        foreach (['min_order_amount','usage_limit','per_user_limit'] as $f) {
            if (!isset($data[$f]) || $data[$f] === '') $data[$f] = null;
        }

        $tz = config('app.timezone', 'Asia/Kolkata');

        $parseLocal = function (?string $val) use ($tz): ?Carbon {
            if (!$val) return null;
            if (preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/', $val)) {
                return Carbon::createFromFormat('Y-m-d\TH:i', $val, $tz);
            }
            if (preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}$/', $val)) {
                return Carbon::createFromFormat('Y-m-d\TH:i:s', $val, $tz);
            }
            return Carbon::parse($val, $tz);
        };

        $data['starts_at']  = $parseLocal($request->input('starts_at'));
        $data['expires_at'] = $parseLocal($request->input('expires_at'));

        if ($data['starts_at'])  $data['starts_at']->second(0);
        if ($data['expires_at']) $data['expires_at']->second(0);

        if (($data['type'] ?? null) === 'percent' && isset($data['value'])) {
            $data['value'] = min(max((float)$data['value'], 0), 100);
        }

        $data['is_active'] = $request->boolean('is_active');

        if (!empty($data['starts_at']) && !empty($data['expires_at']) && $data['expires_at']->lt($data['starts_at'])) {
            $data['expires_at'] = $data['starts_at']->copy();
        }

        if (empty($data['allowed_package_ids'])) {
            $data['allowed_package_ids'] = null;
        }

        return $data;
    }

    /**
     * Determine if a coupon is currently "live".
     * Adjust this logic if your model has its own definition.
     */
    private function isLive(Coupon $coupon): bool
    {
        $now = Carbon::now();

        if (!$coupon->is_active) {
            return false;
        }

        // If starts_at is set and in the future, not live yet
        if ($coupon->starts_at && $coupon->starts_at->isFuture()) {
            return false;
        }

        // If expires_at is set and already past, not live
        if ($coupon->expires_at && $coupon->expires_at->lt($now)) {
            return false;
        }

        return true;
    }

    /**
     * Notify all admins about a coupon status change (live / inactive / expiring).
     */
    private function notifyAdminsCouponStatus(Coupon $coupon, string $status): void
    {
        $admins = User::all();

        foreach ($admins as $admin) {
            $admin->notify(new CouponStatusNotification($coupon, $status));
        }
    }
}
