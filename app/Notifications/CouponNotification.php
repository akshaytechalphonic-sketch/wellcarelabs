<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class CouponNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'type'    => $this->data['type'], // 'coupon_live' or 'coupon_expiring'
            'title'   => $this->data['title'] ?? 'Coupon Update',
            'message' => $this->data['message'] ?? '',
            'url'     => $this->data['url'] ?? route('coupons.index'),
            'coupon'  => [
                'code'       => $this->data['code'],
                'expires_at' => $this->data['expires_at'],
                'discount'   => $this->data['discount'] ?? null,
                'min_order'  => $this->data['min_order'] ?? null,
            ],
        ];
    }
}
