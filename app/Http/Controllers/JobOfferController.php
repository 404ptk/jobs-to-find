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
            'country' => ['nullable', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'string'],
            'employment_type' => ['nullable', 'string', 'in:full-time,part-time,contract,internship'],
            'sort' => ['nullable', 'string', 'in:newest,oldest,salary_high,salary_low'],
        ], [
            'search.regex' => 'Search field can only contain letters, numbers, spaces, and hyphens.',
        ]);

        $query = JobOffer::with(['category', 'location', 'user'])
            ->where('is_active', true)
            ->where('is_approved', true);

        if (!empty($validated['search'])) {
            $query->where(function($q) use ($validated) {
                $q->where('title', 'LIKE', '%' . $validated['search'] . '%')
                  ->orWhere('description', 'LIKE', '%' . $validated['search'] . '%')
                  ->orWhere('company_name', 'LIKE', '%' . $validated['search'] . '%');
            });
        }

        if (!empty($validated['country'])) {
            $query->whereHas('location', function($q) use ($validated) {
                $q->where('country', $validated['country']);
            });
        }

        if (!empty($validated['city'])) {
            $query->whereHas('location', function($q) use ($validated) {
                $q->where('city', $validated['city']);
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

        $sort = $validated['sort'] ?? 'newest';
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'salary_high':
                $query->orderBy('salary_min', 'desc');
                break;
            case 'salary_low':
                $query->orderBy('salary_min', 'asc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $jobOffers = $query->paginate(12)->appends($request->except('page'));

        return view('job-offers', compact('jobOffers', 'validated'));
    }

    public function show($id)
    {
        $jobOffer = JobOffer::with(['category', 'location', 'user'])
            ->where('is_active', true)
            ->findOrFail($id);

        return view('job-detail', compact('jobOffer'));
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

    public function create()
    {
        if (Auth::user()->account_type !== 'employer') {
            abort(403, 'Access denied. Only employers can create job offers.');
        }

        $categories = \App\Models\Category::orderBy('name')->get();
        $locations = \App\Models\Location::orderBy('country')->orderBy('city')->get();
        $employmentTypes = ['full-time', 'part-time', 'contract', 'internship'];

        return view('create-offer', compact('categories', 'locations', 'employmentTypes'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->account_type !== 'employer') {
            abort(403, 'Access denied. Only employers can create job offers.');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:96', 'regex:/^[a-zA-Z0-9\s\-]+$/'],
            'description' => ['required', 'string', 'max:5000'],
            'requirements' => ['required', 'string', 'max:5000'],
            'company_name' => ['required', 'string', 'max:96', 'regex:/^[a-zA-Z0-9\s\-]+$/'],
            'salary_min' => ['nullable', 'numeric', 'min:0'],
            'salary_max' => ['nullable', 'numeric', 'min:0', 'gte:salary_min'],
            'employment_type' => ['required', 'string', 'in:full-time,part-time,contract,internship'],
            'category_id' => ['required', 'exists:categories,id'],
            'location_id' => ['required', 'exists:locations,id'],
            'expires_at' => ['required', 'date', 'after:today'],
        ], [
            'title.regex' => 'Title can only contain letters, numbers, spaces, and hyphens.',
            'company_name.regex' => 'Company name can only contain letters, numbers, spaces, and hyphens.',
            'salary_max.gte' => 'Maximum salary must be greater than or equal to minimum salary.',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['is_active'] = true;
        $validated['is_approved'] = false;
        $validated['currency'] = 'EUR';

        JobOffer::create($validated);

        return redirect()->route('my-offers')->with('success', 'Job offer created successfully! It will be visible after admin approval.');
    }

    public function edit($id)
    {
        if (Auth::user()->account_type !== 'employer') {
            abort(403, 'Access denied. Only employers can edit job offers.');
        }

        $jobOffer = JobOffer::findOrFail($id);

        if ($jobOffer->user_id !== Auth::id()) {
            abort(403, 'Access denied. You can only edit your own job offers.');
        }

        $categories = \App\Models\Category::orderBy('name')->get();
        $locations = \App\Models\Location::orderBy('country')->orderBy('city')->get();
        $employmentTypes = ['full-time', 'part-time', 'contract', 'internship'];

        return view('edit-offer', compact('jobOffer', 'categories', 'locations', 'employmentTypes'));
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->account_type !== 'employer') {
            abort(403, 'Access denied. Only employers can update job offers.');
        }

        $jobOffer = JobOffer::findOrFail($id);

        if ($jobOffer->user_id !== Auth::id()) {
            abort(403, 'Access denied. You can only update your own job offers.');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:96', 'regex:/^[a-zA-Z0-9\s\-]+$/'],
            'description' => ['required', 'string', 'max:5000'],
            'requirements' => ['required', 'string', 'max:5000'],
            'company_name' => ['required', 'string', 'max:96', 'regex:/^[a-zA-Z0-9\s\-]+$/'],
            'salary_min' => ['nullable', 'numeric', 'min:0'],
            'salary_max' => ['nullable', 'numeric', 'min:0', 'gte:salary_min'],
            'employment_type' => ['required', 'string', 'in:full-time,part-time,contract,internship'],
            'category_id' => ['required', 'exists:categories,id'],
            'location_id' => ['required', 'exists:locations,id'],
            'expires_at' => ['required', 'date', 'after:today'],
        ], [
            'title.regex' => 'Title can only contain letters, numbers, spaces, and hyphens.',
            'company_name.regex' => 'Company name can only contain letters, numbers, spaces, and hyphens.',
            'salary_max.gte' => 'Maximum salary must be greater than or equal to minimum salary.',
        ]);

        $jobOffer->update($validated);

        return redirect()->route('my-offers')->with('success', 'Job offer updated successfully!');
    }
}
