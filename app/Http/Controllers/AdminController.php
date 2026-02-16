<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobOffer;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function acceptOffers()
    {
        if (Auth::user()->account_type !== 'admin') {
            abort(403, 'Access denied. Only administrators can access this page.');
        }

        $pendingOffers = JobOffer::with(['category', 'location', 'user'])
            ->where('is_approved', false)
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('admin.accept-offers', compact('pendingOffers'));
    }

    public function approveOffer($id)
    {
        if (Auth::user()->account_type !== 'admin') {
            abort(403, 'Access denied. Only administrators can perform this action.');
        }

        $offer = JobOffer::findOrFail($id);
        $offer->is_approved = true;
        $offer->created_at = now();
        $offer->save();

        return redirect()->route('admin.accept-offers')->with('success', 'Job offer approved successfully!');
    }

    public function rejectOffer($id)
    {
        if (Auth::user()->account_type !== 'admin') {
            abort(403, 'Access denied. Only administrators can perform this action.');
        }

        $offer = JobOffer::findOrFail($id);
        $offer->is_active = false;
        $offer->save();

        return redirect()->route('admin.accept-offers')->with('success', 'Job offer rejected successfully!');
    }

    public function users(Request $request)
    {
        if (Auth::user()->account_type !== 'admin') {
            abort(403, 'Access denied. Only administrators can perform this action.');
        }

        $query = \App\Models\User::query();

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(10);

        $totalUsers = \App\Models\User::count();
        $totalJobSeekers = \App\Models\User::where('account_type', 'job_seeker')->count();
        $totalEmployers = \App\Models\User::where('account_type', 'employer')->count();

        return view('admin.users', compact('users', 'totalUsers', 'totalJobSeekers', 'totalEmployers'));
    }

    public function offers(Request $request)
    {
        if (Auth::user()->account_type !== 'admin') {
            abort(403, 'Access denied. Only administrators can perform this action.');
        }

        $query = JobOffer::with(['user', 'category', 'location']);

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%");
            });
        }

        $offers = $query->orderBy('created_at', 'desc')->paginate(10);

        $totalOffers = JobOffer::count();
        $activeOffers = JobOffer::where('is_active', true)->where('is_approved', true)->count();
        $pendingOffers = JobOffer::where('is_active', true)->where('is_approved', false)->count();

        return view('admin.offers', compact('offers', 'totalOffers', 'activeOffers', 'pendingOffers'));
    }

    public function offerPartial($id) {
        $jobOffer = JobOffer::with(['category', 'location', 'user'])->findOrFail($id);
        return view('components.job-offer-details', compact('jobOffer'));
    }

    public function userPartial($id) {
        $user = User::with(['skills'])->findOrFail($id);
        $availableSkills = \App\Models\Skill::orderBy('name')->get();
        return view('components.user-profile-details', compact('user', 'availableSkills'));
    }
}
