<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\ResetPasswordOtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ForgotPasswordController extends Controller
{
    /**
     * Show the forgot password view.
     */
    public function show()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle the forgot password request by sending an OTP.
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $user = User::where('email', $request->email)->first();
        
        $otp = (string) random_int(100000, 999999);
        $user->reset_otp = $otp;
        $user->reset_otp_expires_at = now()->addMinutes(10);
        $user->save();

        try {
            defer(fn () => $user->notify(new ResetPasswordOtp($otp)));
            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Security code dispatched.']);
            }
            return redirect()->route('password.reset', ['token' => 'otp'])
                           ->with('email', $request->email)
                           ->with('success', 'A password reset code has been sent to your email.');
        } catch (\Exception $e) {
            Log::error("Reset OTP Mail Error: " . $e->getMessage());
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Transmission failed.'], 500);
            }
            return back()->withErrors(['email' => 'We encountered an error sending the reset code. Please try again.']);
        }
    }

    /**
     * Verify the OTP via AJAX for multi-step flow.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|string|size:6',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user->reset_otp || $user->reset_otp !== $request->otp) {
            return response()->json(['success' => false, 'message' => 'Invalid security code.'], 422);
        }

        if ($user->reset_otp_expires_at && $user->reset_otp_expires_at->isPast()) {
            return response()->json(['success' => false, 'message' => 'Security code expired.'], 422);
        }

        return response()->json(['success' => true, 'message' => 'Identity verified.']);
    }
}
