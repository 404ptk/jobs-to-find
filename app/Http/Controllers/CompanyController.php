<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = auth()->user()->companies;
        return view('companies.index', compact('companies'));
    }

    public function create()
    {
        if (auth()->user()->companies()->count() >= 3) {
            return redirect()->route('companies.index')->with('error', 'You can only have up to 3 companies.');
        }
        return view('companies.create');
    }

    public function store(\Illuminate\Http\Request $request)
    {
        if (auth()->user()->companies()->count() >= 3) {
            return redirect()->route('companies.index')->with('error', 'You can only have up to 3 companies.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|max:2048',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'founded_at' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'nip' => 'required|string|max:20',
        ]);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $validated['logo_path'] = $path;
        }

        unset($validated['logo']);
        auth()->user()->companies()->create($validated);

        return redirect()->route('companies.index')->with('success', 'Company created successfully.');
    }

    public function edit($id)
    {
        $company = auth()->user()->companies()->findOrFail($id);
        return view('companies.edit', compact('company'));
    }

    public function update(\Illuminate\Http\Request $request, $id)
    {
        $company = auth()->user()->companies()->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|max:2048',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'founded_at' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'nip' => 'required|string|max:20',
        ]);

        if ($request->hasFile('logo')) {
            if ($company->logo_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($company->logo_path);
            }
            $path = $request->file('logo')->store('logos', 'public');
            $validated['logo_path'] = $path;
        }

        unset($validated['logo']);
        $company->update($validated);

        return redirect()->route('companies.index')->with('success', 'Company updated successfully.');
    }

    public function destroy($id)
    {
        $company = auth()->user()->companies()->findOrFail($id);
        if ($company->logo_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($company->logo_path);
        }
        $company->delete();

        return redirect()->route('companies.index')->with('success', 'Company deleted successfully.');
    }
}
