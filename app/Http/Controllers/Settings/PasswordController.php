<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Inertia\Response;

class PasswordController extends Controller
{
    /**
     * Show the user's password settings page.
     */
    public function edit(Request $request): Response
    {
        $user = $request->user();

        return Inertia::render('settings/password', [
            'requiresCurrentPassword' => $user->hasPassword() && !$user->isSocialUser(),
            'isSocialUser' => $user->isSocialUser(),
            'hasPassword' => $user->hasPassword(),
        ]);
    }

    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Define validation rules based on user type
        $rules = [
            'password' => ['required', Password::defaults(), 'confirmed'],
        ];

        // Only require current password if user has a password set
        // This allows social login users to set a password for the first time
        if ($user->hasPassword() && !$user->isSocialUser()) {
            $rules['current_password'] = ['required', 'current_password'];
        } elseif ($user->hasPassword()) {
            // For social users who already have a password, make current_password optional
            $rules['current_password'] = ['nullable', 'current_password'];
        }

        $validated = $request->validate($rules);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back();
    }
}
