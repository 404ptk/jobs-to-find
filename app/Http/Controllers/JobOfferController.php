<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobOffer;
use Illuminate\Support\Facades\Auth;

class JobOfferController extends Controller
{
    public function search(Request $request)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100', 'regex:/^[a-zA-Z0-9\s\-]+$/'],
            'location' => ['nullable', 'string', 'max:100', 'regex:/^[a-zA-Z\s\-]+$/'],
            'category' => ['nullable', 'string', 'in:it,marketing,hr,sales,finance'],
            'employment_type' => ['nullable', 'string', 'in:full-time,part-time,contract,internship'],
        ], [
            'search.regex' => 'Search field can only contain letters, numbers, spaces, and hyphens.',
            'location.regex' => 'Location field can only contain letters, spaces, and hyphens.',
        ]);

        $query = JobOffer::with(['category', 'location', 'user'])
            ->where('is_active', true);

        if (!empty($validated['search'])) {
            $query->where(function($q) use ($validated) {
                $q->where('title', 'LIKE', '%' . $validated['search'] . '%')
                  ->orWhere('description', 'LIKE', '%' . $validated['search'] . '%')
                  ->orWhere('company_name', 'LIKE', '%' . $validated['search'] . '%');
            });
        }

        if (!empty($validated['location'])) {
            $query->whereHas('location', function($q) use ($validated) {
                $q->where('city', 'LIKE', '%' . $validated['location'] . '%')
                  ->orWhere('region', 'LIKE', '%' . $validated['location'] . '%');
            });
        }

        if (!empty($validated['category'])) {
            $query->whereHas('category', function($q) use ($validated) {
                $q->where('slug', $validated['category']);
            });
        }

        if (!empty($validated['employment_type'])) {
            $query->where('employment_type', $validated['employment_type']);
        }

        $jobOffers = $query->orderBy('created_at', 'desc')->paginate(12);

        return view('job-offers', compact('jobOffers', 'validated'));
    }

    public function myOffers()
    {
        if (Auth::user()->account_type !== 'employer') {
            abort(403, 'Access denied. Only employers can view this page.');
        }

        $jobOffers = JobOffer::where('user_id', Auth::id())
            ->with(['category', 'location'])
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        return view('my-offers', compact('jobOffers'));
    }

    public function myApplications()
    {
        if (Auth::user()->account_type !== 'job_seeker') {
            abort(403, 'Access denied. Only job seekers can view this page.');
        }

        // TODO: Implement logic
        return view('my-applications');
    }
}
