<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $regions = \App\Services\ShippingService::getRegions();
        $regionalCities = \App\Services\ShippingService::getRegionalCities();
        return view('profile.index', compact('user', 'regions', 'regionalCities'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => ['nullable', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:11|regex:/^[0-9]+$/',
            'region' => 'nullable|string',
            'city' => 'nullable|string',
            'default_shipping_address' => 'nullable|string',
            'password' => 'nullable|min:8|confirmed',
        ], [
            'name.regex' => 'The name field should only contain letters and spaces.',
        ]);

        if ($request->filled('name')) {
            $user->name = $request->name;
        }
        $user->email = $request->email;
        
        $phone = $request->phone;
        if ($phone && !str_starts_with($phone, '+63')) {
            $phone = '+63' . ltrim($phone, '0');
        }
        $user->phone = $phone;

        $user->region = $request->region;
        $user->city = $request->city;
        $user->default_shipping_address = $request->default_shipping_address;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully!');
    }
}
