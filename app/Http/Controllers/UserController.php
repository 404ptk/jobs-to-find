<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function show($username)
    {
        $user = User::where('username', $username)->firstOrFail();
        $currentUser = Auth::user();

        // 1. User can view their own profile.
        // 2. Admin can view anyone's profile.
        // 3. Employer can view job seeker's profile.
        // 4. Job Seeker CANNOT view another job seeker.

        $canView = false;

        if ($currentUser->id === $user->id) {
            $canView = true;
        } elseif ($currentUser->account_type === 'admin') {
            $canView = true;
        } elseif ($user->account_type === 'job_seeker' && $currentUser->account_type === 'employer') {
            $canView = true;
        } elseif ($user->account_type === 'job_seeker' && $currentUser->account_type === 'job_seeker') {
            $canView = false;
        }

        if ($user->account_type === 'employer' && $currentUser->id !== $user->id && $currentUser->account_type !== 'admin') {
            $canView = false;
        }

        if (!$canView) {
            abort(403, 'You are not authorized to view this profile.');
        }

        $availableSkills = \App\Models\Skill::orderBy('name')->get();

        $applicationStats = [
            'total' => $user->applications()->count(),
            'accepted' => $user->applications()->where('status', 'accepted')->count(),
            'rejected' => $user->applications()->where('status', 'rejected')->count(),
        ];

        $offerStats = null;
        if ($user->account_type === 'employer') {
            $offerStats = [
                'total' => $user->jobOffers()->count(),
                'active' => $user->jobOffers()->where('is_active', true)->where('is_approved', true)->count(),
                'pending' => $user->jobOffers()->where('is_approved', false)->count(),
            ];
        }

        return view('auth.profile', compact('user', 'availableSkills', 'applicationStats', 'offerStats'));
    }
}
