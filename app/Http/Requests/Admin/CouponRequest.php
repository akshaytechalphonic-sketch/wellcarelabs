<?php
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CouponRequest extends FormRequest
{
    public function authorize(): bool { return true; } // add your policy if needed

    public function rules(): array {
        $id = $this->route('coupon')?->id;
        return [
            'code' => 'required|string|max:50|unique:coupons,code,'.($id ?? 'NULL').',id',
            'type' => 'required|in:percent,fixed',
            'value' => 'required|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'per_user_limit' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'is_active' => 'boolean',
            'allowed_package_ids' => 'nullable|array',
            'allowed_package_ids.*' => 'integer',
        ];
    }
}
