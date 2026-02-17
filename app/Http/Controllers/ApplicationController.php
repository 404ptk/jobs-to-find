<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\JobOffer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function apply(Request $request, $jobOfferId)
    {
        try {
            $jobOffer = JobOffer::findOrFail($jobOfferId);

            $existingApplication = Application::where('user_id', Auth::id())
                ->where('job_offer_id', $jobOfferId)
                ->first();

            if ($existingApplication) {
                if ($request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => 'You have already applied to this position.'], 400);
                }
                return redirect()->back()->with('error', 'You have already applied to this position.');
            }

            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'cv' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            ]);

            $cvPath = null;
            if ($request->hasFile('cv')) {
            }

            Application::create([
                'user_id' => Auth::id(),
                'job_offer_id' => $jobOfferId,
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'cv_path' => $cvPath,
                'status' => 'pending',
            ]);

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Your application has been submitted successfully!']);
            }
            return redirect()->back()->with('success', 'Your application has been submitted successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Validation failed. Please check your inputs.',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function myApplications(Request $request)
    {
        $perPage = $request->input('per_page', 9);

        $applications = Application::with(['jobOffer.category', 'jobOffer.location'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return view('job-seeker.my-applications', compact('applications'));
    }
}
