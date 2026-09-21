<?php

namespace App\Services;

use App\Models\Cart;
use Illuminate\Http\Request;

class CartResolver
{
    public static function forRequest(Request $request): Cart
    {
        $sessionId = $request->session()->getId();

        $cart = Cart::where('session_id',$sessionId)
            ->where('status','active')
            ->latest('id')->first();

        if (!$cart) {
            $cart = Cart::create([
                'session_id' => $sessionId,
                'status'     => 'active',
            ]);
        }
        return $cart->load('items');
    }
}
