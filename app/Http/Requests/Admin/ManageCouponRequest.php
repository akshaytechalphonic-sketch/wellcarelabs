<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class ManageCouponRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // add your auth logic if needed
    }

    public function rules(): array
    {
        $couponId = $this->route('coupon')?->id;

        return [
            'code' => ['required','string','max:64', Rule::unique('coupons','code')->ignore($couponId)],
            'type' => ['required', Rule::in(['percent','fixed'])],
            'value' => ['required','numeric','min:0'],

            'max_discount'     => ['nullable','numeric','min:0'],
            'min_order_amount' => ['nullable','numeric','min:0'],
            'usage_limit'      => ['nullable','integer','min:1'],
            'per_user_limit'   => ['nullable','integer','min:1'],

            'starts_at'  => ['nullable','date'],
            'expires_at' => ['nullable','date','after_or_equal:starts_at'],

            'is_active' => ['nullable', Rule::in(['0','1'])],

            // ✅ NEW FIELD
            'show_on_frontend' => ['nullable', Rule::in(['0','1'])],

            'allowed_package_ids' => ['nullable'], // adjust if array/json
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($v) {
            if ($this->input('type') === 'percent' && (float)$this->input('value') > 100) {
                $v->errors()->add('value', 'Percentage cannot exceed 100%.');
            }
        });
    }

    public function messages(): array
    {
        return [
            'code.unique' => 'This coupon code is already in use.',
            'expires_at.after_or_equal' => 'Expires At must be after or equal to Starts At.',
        ];
    }

    /**
     * Compatibility wrapper: returns validated and normalized data.
     * Keeps controller code that calls $request->validatedData() working.
     *
     * @return array
     */
    public function validatedData(): array
    {
        $data = $this->validated();

        // convert empty numeric-ish fields to null
        foreach (['max_discount', 'min_order_amount', 'usage_limit', 'per_user_limit'] as $f) {
            if (!isset($data[$f]) || $data[$f] === '') {
                $data[$f] = null;
            }
        }

        // timezone for parsing (falls back to app timezone)
        $tz = config('app.timezone', 'Asia/Kolkata');

        $parseLocal = function (?string $val) use ($tz): ?Carbon {
            if (!$val) return null;

            // support 'YYYY-MM-DDTHH:MM' and 'YYYY-MM-DDTHH:MM:SS' inputs from HTML5 datetime-local
            if (preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/', $val)) {
                return Carbon::createFromFormat('Y-m-d\TH:i', $val, $tz);
            }
            if (preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}$/', $val)) {
                return Carbon::createFromFormat('Y-m-d\TH:i:s', $val, $tz);
            }
            // fallback to Carbon parse with timezone
            return Carbon::parse($val, $tz);
        };

        $data['starts_at']  = $parseLocal($this->input('starts_at'));
        $data['expires_at'] = $parseLocal($this->input('expires_at'));

        if ($data['starts_at'])  $data['starts_at']->second(0);
        if ($data['expires_at']) $data['expires_at']->second(0);

        // clamp percent values if relevant
        if (($data['type'] ?? null) === 'percent' && isset($data['value'])) {
            $data['value'] = min(max((float)$data['value'], 0), 100);
        }

        // normalize is_active to boolean
        $data['is_active'] = $this->boolean('is_active');

        // ✅ normalize show_on_frontend to boolean
        $data['show_on_frontend'] = $this->boolean('show_on_frontend');

        // if expires < starts, make them equal
        if (!empty($data['starts_at']) && !empty($data['expires_at']) && $data['expires_at']->lt($data['starts_at'])) {
            $data['expires_at'] = $data['starts_at']->copy();
        }

        if (empty($data['allowed_package_ids'])) {
            $data['allowed_package_ids'] = null;
        }

        return $data;
    }
}
