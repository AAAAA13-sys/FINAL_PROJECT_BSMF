<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

use App\Traits\LogsActions;

final class AuthController extends Controller
{
    use LogsActions;
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


            $request->session()->regenerate();

            if ($user->isAdministrative()) {
                $this->logAction('LOGIN', "User logged in: {$user->username}");
                return redirect()->route('admin.dashboard')->with('success', "Welcome back, {$user->name}!");
            }

            if (!$user->hasVerifiedEmail()) {
                return redirect()->route('verification.notice')->with('success', 'Please verify your account to continue.');
            }

            return redirect()->intended(route('home'))->with('success', "Welcome back, {$user->name}!");
        }

        return back()->withErrors([
            'email' => 'Incorrect username/password',
        ])->withInput($request->only('email'));
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
            'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'username' => 'required|string|max:50|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
        ], [
            'name.regex' => 'The name field should only contain letters and spaces.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'customer',
        ]);

        $mailStatus = '';
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


        Auth::login($user);

        return redirect()->route('verification.notice')->with('success', $mailStatus);
    }


    /**
     * Handle logout.
     */
    public function logout(Request $request)
    {

        if (auth()->check() && auth()->user()->isAdministrative()) {
            $this->logAction('LOGOUT', "User logged out: " . auth()->user()->username);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'Logged out. See you next time!');
    }
}
