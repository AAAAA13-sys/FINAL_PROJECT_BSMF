<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class VerificationController extends Controller
{
    /**
     * Show the verification notice view.
     */
    public function show()
    {
        return Auth::user()->hasVerifiedEmail()
            ? redirect()->route('home')
            : view('auth.verify-otp');
    }

    /**
     * Handle the OTP verification request.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $user = Auth::user();

        if (!$user->otp || $user->otp !== $request->otp || now()->gt($user->otp_expires_at)) {
            return back()->withErrors(['otp' => 'The provided code is invalid or has expired.']);
        }

        $user->markEmailAsVerified();
        $user->update([
            'otp' => null,
            'otp_expires_at' => null,
        ]);

        return redirect()->route('home')->with('success', 'Email verified successfully! Welcome to BSMF Garage.');
    }

    /**
     * Resend the verification OTP.
     */
    public function resend(Request $request)
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('home');
        }

        $user->sendVerificationOtp();

        return back()->with('success', 'A new verification code has been sent to your email.');
    }
}
