<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;   // <-- add this line
use App\Services\DovesoftService;

class WhatsappController extends Controller
{
    protected DovesoftService $dovesoft;

    public function __construct(DovesoftService $dovesoft)
    {
        $this->dovesoft = $dovesoft;
    }

    public function testSend()
    {
        $to = '918689964627';
        $text = 'Hello Rahul Here IS your test message from Dovesoft API!';

        $result = $this->dovesoft->sendWhatsApp($to, $text);

        return response()->json($result);
    }
}
