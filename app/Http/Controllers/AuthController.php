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
            'bio' => ['nullable', 'string', 'max:1000'],
            'github_url' => ['nullable', 'url', 'max:255', 'regex:/^https:\/\/(www\.)?github\.com\/[a-zA-Z0-9-]+\/?$/'],
            'linkedin_url' => ['nullable', 'url', 'max:255', 'regex:/^https:\/\/(www\.)?linkedin\.com\/in\/[a-zA-Z0-9-]+\/?$/'],
            'avatar' => ['nullable', 'image', 'max:2048'], // Max 2MB
            'privacy_settings' => ['nullable', 'array'],
            'privacy_settings.*' => ['nullable', 'in:0,1'],
        ], [
            'first_name.regex' => 'First name can only contain letters and spaces.',
            'last_name.regex' => 'Last name can only contain letters and spaces.',
            'date_of_birth.before' => 'Date of birth must be in the past.',
            'github_url.url' => 'Please provide a valid GitHub profile URL.',
            'github_url.regex' => 'The GitHub URL must be a valid GitHub profile link.',
            'linkedin_url.url' => 'Please provide a valid LinkedIn profile URL.',
            'linkedin_url.regex' => 'The LinkedIn URL must be a valid LinkedIn profile link.',
            'avatar.image' => 'The file must be an image.',
            'avatar.max' => 'The image size must not exceed 2MB.',
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            }
            
            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $path;
        }

        if ($user->account_type === 'job_seeker') {
            $validated['is_student'] = $request->has('is_student');
        } else {
            unset($validated['is_student']);
        }

        $user->update($validated);

        return redirect()->route('profile')->with('success', 'Profile updated successfully!');
    }
}
