<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginField = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        
        $user = User::where($loginField, $credentials['login'])->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            Auth::login($user, $request->filled('remember'));
            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'login' => 'Invalid credentials.',
        ])->onlyInput('login');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:28', 'unique:users', 'regex:/^[a-zA-Z0-9_]+$/'],
            'first_name' => ['required', 'string', 'max:32', 'regex:/^[a-zA-Z\s]+$/'],
            'last_name' => ['required', 'string', 'max:32', 'regex:/^[a-zA-Z\s]+$/'],
            'email' => 'required|string|email|max:48|unique:users',
            'password' => ['required', 'string', 'min:8', 'confirmed', 'regex:/^[a-zA-Z0-9@#$%^&+=!]+$/'],
            'account_type' => 'required|in:job_seeker,employer',
        ], [
            'username.regex' => 'Username can only contain letters, numbers, and underscores.',
            'first_name.regex' => 'First name can only contain letters and spaces.',
            'last_name.regex' => 'Last name can only contain letters and spaces.',
            'password.regex' => 'Password can only contain letters, numbers, and basic special characters (@#$%^&+=!).',
        ]);

        $user = User::create([
            'username' => $validated['username'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'account_type' => $validated['account_type'],
            'country' => 'Poland',
            'is_student' => false,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect('/');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:32', 'regex:/^[a-zA-Z\s]+$/'],
            'last_name' => ['required', 'string', 'max:32', 'regex:/^[a-zA-Z\s]+$/'],
            'country' => ['required', 'string', 'max:64'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'is_student' => ['nullable', 'boolean'],
        ], [
            'first_name.regex' => 'First name can only contain letters and spaces.',
            'last_name.regex' => 'Last name can only contain letters and spaces.',
            'date_of_birth.before' => 'Date of birth must be in the past.',
        ]);

        if ($user->account_type === 'job_seeker') {
            $validated['is_student'] = $request->has('is_student');
        } else {
            unset($validated['is_student']);
        }

        $user->update($validated);

        return redirect()->route('profile')->with('success', 'Profile updated successfully!');
    }
}
