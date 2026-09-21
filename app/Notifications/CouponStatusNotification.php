<?php

namespace App\Notifications;

use App\Models\Coupon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CouponStatusNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Coupon $coupon,
        public string $status // 'live' | 'expiring' | 'inactive'
    ) {}

    /**
     * Channels: we only use 'database' for now.
     */
    public function via(object $notifiable): array
    {
        if ($notifiable->role !== 'admin') {
            return [];
        }

        return ['database'];
    }


    /**
     * Payload that goes into `notifications` table.
     */
    public function toDatabase(object $notifiable): array
    {
        $type = match ($this->status) {
            'live'     => 'coupon_live',
            'expiring' => 'coupon_expiring',
            'inactive' => 'coupon_inactive',
            default    => 'coupon_live',
        };

        $title = match ($type) {
            'coupon_live'      => 'Coupon Live',
            'coupon_expiring'  => 'Coupon Expiring Soon',
            'coupon_inactive'  => 'Coupon Inactivated',
            default            => 'Coupon Update',
        };

        $message = match ($type) {
            'coupon_live'      => "Coupon {$this->coupon->code} is now live.",
            'coupon_expiring'  => "Coupon {$this->coupon->code} is expiring soon.",
            'coupon_inactive'  => "Coupon {$this->coupon->code} has been marked inactive.",
            default            => "Coupon {$this->coupon->code} status updated.",
        };

        // Optional: build discount string if you have type/value
        $discount = null;
        if (isset($this->coupon->type, $this->coupon->value)) {
            if ($this->coupon->type === 'percent') {
                $discount = $this->coupon->value . '% off';
            } else {
                $discount = '₹' . (float) $this->coupon->value . ' off';
            }
        }

        return [
            'type'    => $type,   // used by Blade for icon, title, filter
            'title'   => $title,
            'message' => $message,
            'url'     => route('admin.coupons.index', ['q' => $this->coupon->code]),

            'coupon'  => [
                'code'       => $this->coupon->code,
                'expires_at' => optional($this->coupon->expires_at)->toDateString(),
                'discount'   => $discount,
                'min_order'  => $this->coupon->min_order_amount ?? null,
            ],
        ];
    }
}
