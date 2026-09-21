<?php

namespace App\Providers;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword; // ✅ correct
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
            ResetPassword::toMailUsing(function ($notifiable, $token) {
        $resetUrl = url(route('password.reset', [
            'token' => $token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Reset your WellCare password')
            ->view('emails.reset-password', [
                'token'    => $token,
                'email'    => $notifiable->getEmailForPasswordReset(),
                'resetUrl' => $resetUrl,
                'logoUrl'  => asset('assets\images\wellcare_logo.png'),
            ]);
    });
    }
}
