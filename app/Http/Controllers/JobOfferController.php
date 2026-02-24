<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobOffer;
use App\Models\Category;
use App\Models\Location;
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
            'per_page' => ['nullable', 'integer', 'in:10,20,30'],
        ], [
            'search.regex' => 'Search field can only contain letters, numbers, spaces, and hyphens.',
        ]);

        $query = JobOffer::with(['category', 'location', 'user'])
            ->where('is_active', true)
            ->where('is_approved', true);

        if (!empty($validated['search'])) {
            $query->where(function ($q) use ($validated) {
                $q->where('title', 'LIKE', '%' . $validated['search'] . '%')
                    ->orWhere('description', 'LIKE', '%' . $validated['search'] . '%')
                    ->orWhere('company_name', 'LIKE', '%' . $validated['search'] . '%');
            });
        }

        if (!empty($validated['country'])) {
            $query->whereHas('location', function ($q) use ($validated) {
                $q->where('country', $validated['country']);
            });
        }

        if (!empty($validated['city'])) {
            $query->whereHas('location', function ($q) use ($validated) {
                $q->where('city', $validated['city']);
            });
        }

        if (!empty($validated['category'])) {
            $query->whereHas('category', function ($q) use ($validated) {
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

        $perPage = $request->input('per_page', 10);
        $jobOffers = $query->paginate($perPage)->appends($request->except('page'));

        $categories = Category::whereHas('jobOffers', function ($q) {
            $q->where('is_active', true)->where('is_approved', true);
        })->orderBy('name')->get();

        $countries = Location::whereHas('jobOffers', function ($q) {
            $q->where('is_active', true)->where('is_approved', true);
        })->select('country')->distinct()->orderBy('country')->pluck('country');

        $cities = Location::whereHas('jobOffers', function ($q) {
            $q->where('is_active', true)->where('is_approved', true);
        })->select('city')->distinct()->orderBy('city')->pluck('city');

        $employmentTypes = JobOffer::where('is_active', true)
            ->where('is_approved', true)
            ->select('employment_type')
            ->distinct()
            ->orderBy('employment_type')
            ->pluck('employment_type');

        $favoriteIds = [];
        if (Auth::check() && Auth::user()->account_type === 'job_seeker') {
            $favoriteIds = Auth::user()->favoriteOffers()->pluck('job_offer_id')->toArray();
        }

        return view('public.job-offers', compact('jobOffers', 'validated', 'categories', 'countries', 'cities', 'employmentTypes', 'favoriteIds'));
    }

    public function show($id)
    {
        $jobOffer = JobOffer::with(['category', 'location', 'user', 'applications'])
            ->where('is_active', true)
            ->findOrFail($id);

        if (!$jobOffer->is_approved) {
            if (
                !Auth::check() ||
                (Auth::user()->account_type !== 'admin' && Auth::id() !== $jobOffer->user_id)
            ) {
                abort(403, 'This job offer is pending approval and cannot be viewed at this time.');
            }
        }

        $jobOffer->increment('views_count');

        $isFavorited = false;
        if (Auth::check() && Auth::user()->account_type === 'job_seeker') {
            $isFavorited = Auth::user()->favoriteOffers()->where('job_offer_id', $jobOffer->id)->exists();
        }

        return view('public.job-detail', compact('jobOffer', 'isFavorited'));
    }

    public function myOffers(Request $request)
    {
        if (Auth::user()->account_type !== 'employer') {
            abort(403, 'Access denied. Only employers can view this page.');
        }

        $perPage = $request->input('per_page', 9);

        $jobOffers = JobOffer::where('user_id', Auth::id())
            ->with(['category', 'location'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->appends($request->except('page'));

        return view('employer.my-offers', compact('jobOffers'));
    }

    public function myApplications()
    {
        if (Auth::user()->account_type !== 'job_seeker') {
            abort(403, 'Access denied. Only job seekers can view this page.');
        }

        // TODO: Implement logic
        return view('job-seeker.my-applications');
    }

    public function create()
    {
        if (Auth::user()->account_type !== 'employer') {
            abort(403, 'Access denied. Only employers can create job offers.');
        }

        $categories = \App\Models\Category::orderBy('name')->get();
        $locations = \App\Models\Location::orderBy('country')->orderBy('city')->get();
        $employmentTypes = ['full-time', 'part-time', 'contract', 'internship'];

        return view('employer.create-offer', compact('categories', 'locations', 'employmentTypes'));
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

        return view('employer.edit-offer', compact('jobOffer', 'categories', 'locations', 'employmentTypes'));
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

    public function destroy($id)
    {
        if (Auth::user()->account_type !== 'employer') {
            abort(403, 'Access denied. Only employers can delete job offers.');
        }

        $jobOffer = JobOffer::findOrFail($id);

        if ($jobOffer->user_id !== Auth::id()) {
            abort(403, 'Access denied. You can only delete your own job offers.');
        }

        $jobOffer->delete();

        return redirect()->route('my-offers')->with('success', 'Job offer deleted successfully!');
    }
}
