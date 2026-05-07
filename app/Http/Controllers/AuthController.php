<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

final class AuthController extends Controller
{
    /**
     * Show login view.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle authentication attempt.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string', // This field will hold the username
            'password' => 'required',
        ]);

        // Map 'email' field to 'username' for Auth::attempt
        $authData = [
            'username' => $credentials['email'],
            'password' => $credentials['password'],
        ];

        if (Auth::attempt($authData)) {
            $user = Auth::user();
            
            if ($request->wantsJson() || $request->is('api/*')) {
                $token = $user->createToken('auth_token')->plainTextToken;
                return response()->json([
                    'message' => 'Logged in successfully',
                    'user' => new \App\Http\Resources\UserResource($user),
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                ]);
            }

            $request->session()->regenerate();
            
            if ($user->isAdministrative()) {
                return redirect()->route('admin.dashboard')->with('success', "Welcome back, {$user->name}!");
            }
            
            return redirect()->intended(route('home'))->with('success', "Welcome back, {$user->name}!");
        }

        if ($request->wantsJson() || $request->is('api/*')) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return back()->withErrors([
            'email' => 'Invalid credentials',
        ]);
    }

    /**
     * Show registration view.
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle new user registration.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'customer', // HARD SECURITY: Never allow role injection from request
        ]);

        $mailStatus = '';
        // BYPASS: Admin and Staff don't need OTP during development
        if ($user->isAdministrative()) {
            $user->email_verified_at = now();
            $user->save();
            $mailStatus = 'Administrative account active. OTP bypassed.';
        } else {
            try {
                $user->sendVerificationOtp();
                $mailStatus = 'Verification code sent to your email.';
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Mail Error: " . $e->getMessage());
                $mailStatus = 'Account created! However, we had trouble sending the verification code. Please try resending it from this page.';
            }
        }

        if ($request->wantsJson() || $request->is('api/*')) {
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'message' => 'User registered successfully. ' . $mailStatus,
                'user' => new \App\Http\Resources\UserResource($user),
                'access_token' => $token,
                'token_type' => 'Bearer',
            ], 201);
        }

        Auth::login($user);

        return redirect()->route('verification.notice')->with('success', $mailStatus);
    }

    /**
     * Get authenticated user profile.
     */
    public function profile(Request $request)
    {
        return new \App\Http\Resources\UserResource($request->user());
    }

    /**
     * Handle logout.
     */
    public function logout(Request $request)
    {
        if ($request->wantsJson() || $request->is('api/*')) {
            $request->user()->currentAccessToken()->delete();
            return response()->json(['message' => 'Logged out successfully']);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'Logged out. See you next time!');
    }
}
