<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules;

class ProfileController extends Controller
{
    /**
     * Display the hospital manager's profile.
     */
    public function show(Request $request)
    {
        $user = $request->user();
        $hospital = $user->ownedHospital;

        return view('partials.shared-profile', [
            'user' => $user,
            'hospital' => $hospital,
            'roleName' => 'Hospital Manager',
            'roleIcon' => 'fa-user-md',
            'dashboardRoute' => route('hospital.dashboard'),
            'profileRoute' => route('hospital.profile.show'),
            'updatePasswordRoute' => route('hospital.profile.updatePassword'),
            'sendResetLinkRoute' => route('hospital.profile.sendResetLink'),
            'backRoute' => route('hospital.dashboard'),
            'isAdmin' => false,
            'isHospitalManager' => true,
        ]);
    }

    /**
     * Update the user's profile information.
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
     * Update the user's password.
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