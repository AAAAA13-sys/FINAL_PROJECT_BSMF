<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ResetPasswordController extends Controller
{
    /**
     * Show the reset password view.
     */
    public function show(Request $request)
    {
        return view('auth.reset-password')->with(
            ['email' => session('email') ?? $request->email]
        );
    }

    /**
     * Handle the password reset by verifying OTP.
     */
    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|string|size:6',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::where('email', $request->email)->first();

        // Check OTP and Expiry
        if (!$user->reset_otp || $user->reset_otp !== $request->otp) {
            return back()->withErrors(['otp' => 'The provided security code is invalid.']);
        }

        if ($user->reset_otp_expires_at && $user->reset_otp_expires_at->isPast()) {
            return back()->withErrors(['otp' => 'The security code has expired. Please request a new one.']);
        }

        // Update Password
        $user->password = Hash::make($request->password);
        $user->reset_otp = null;
        $user->reset_otp_expires_at = null;
        
        // Also verify email if it wasn't verified yet (since they have access to the email)
        if (!$user->hasVerifiedEmail()) {
            $user->email_verified_at = now();
        }
        
        $user->save();

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Credentials updated.']);
        }

        return redirect()->route('login')->with('success', 'Your credentials have been updated. You may now ignite your engine.');
    }
}
