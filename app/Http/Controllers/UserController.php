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
        } elseif ($currentUser->account_type === 'employer' && $user->account_type === 'job_seeker') {
            $canView = true;
        }

        if (!$canView) {
            abort(403, 'You are not authorized to view this profile.');
        }

        return view('user.show', compact('user'));
    }
}
