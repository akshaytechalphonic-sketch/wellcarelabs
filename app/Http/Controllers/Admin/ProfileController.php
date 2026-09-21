<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules;

class ProfileController extends Controller
{
    /**
     * Display the admin's profile.
     */
    public function show(Request $request)
    {
        $user = $request->user();
        
        return view('partials.shared-profile', [
            'user' => $user,
            'hospital' => null,
            'roleName' => 'System Administrator',
            'roleIcon' => 'fa-user-shield',
            'dashboardRoute' => route('admin.dashboard'),
            'profileRoute' => route('admin.profile.show'),
            'updatePasswordRoute' => route('admin.profile.updatePassword'),
            'sendResetLinkRoute' => route('admin.profile.sendResetLink'),
            'backRoute' => route('admin.dashboard'),
            'isAdmin' => true,
            'isHospitalManager' => false,
        ]);
    }

    /**
     * Update the admin's profile information.
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $user = $request->user();
        $user->update([
            'name' => $request->name,
        ]);

        return back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Update the admin's password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = $request->user();
        
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Password updated successfully!');
    }

    /**
     * Send password reset link via email.
     */
    public function sendResetLink(Request $request)
    {
        $user = $request->user();

        $status = Password::sendResetLink(['email' => $user->email]);

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with(
                'success',
                'Password reset link has been emailed to ' . $user->email . '.'
            );
        }

        return back()->withErrors(['email' => __($status)]);
    }
}