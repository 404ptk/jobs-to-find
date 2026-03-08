@extends('layouts.app')

@section('title', 'Edit Company - Jobs to Find')

@section('content')
  <div class="min-h-[calc(100vh-8rem)] py-12 px-4">
    <div class="max-w-3xl mx-auto">
      <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('companies.index') }}"
          class="p-2 bg-white border border-gray-200 rounded-lg text-gray-600 hover:text-blue-600 hover:border-blue-200 transition">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
        </a>
        <div>
          <h1 class="text-3xl font-bold text-gray-900">Edit Company</h1>
          <p class="text-gray-600">Update details for {{ $company->name }}</p>
        </div>
      </div>

      <form action="{{ route('companies.update', $company->id) }}" method="POST" enctype="multipart/form-data"
        class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @csrf
        @method('PUT')
        <div class="p-8">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="col-span-2">
              <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Company Name</label>
              <input type="text" name="name" id="name" value="{{ old('name', $company->name) }}" required
                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                placeholder="e.g. Acme Corporation">
              @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="col-span-2 md:col-span-1">
              <label for="nip" class="block text-sm font-semibold text-gray-700 mb-2">NIP (Tax ID)</label>
              <input type="text" name="nip" id="nip" value="{{ old('nip', $company->nip) }}" required
                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                placeholder="1234567890">
              @error('nip') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="col-span-2 md:col-span-1">
              <label for="location" class="block text-sm font-semibold text-gray-700 mb-2">Location</label>
              <input type="text" name="location" id="location" value="{{ old('location', $company->location) }}" required
                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                placeholder="e.g. Warsaw, Poland">
              @error('location') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="col-span-2 md:col-span-1">
              <label for="founded_at" class="block text-sm font-semibold text-gray-700 mb-2">Founded Year</label>
              <input type="number" name="founded_at" id="founded_at" value="{{ old('founded_at', $company->founded_at) }}"
                min="1900" max="{{ date('Y') + 1 }}"
                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                placeholder="e.g. 2020">
              @error('founded_at') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="col-span-2 md:col-span-1">
              <label for="logo" class="block text-sm font-semibold text-gray-700 mb-2">Company Logo (Optional)</label>
              <div class="flex items-center gap-4">
                @if($company->logo_path)
                  <img src="{{ asset('storage/' . $company->logo_path) }}" alt="Current Logo"
                    class="w-12 h-12 object-cover rounded border border-gray-200">
                @endif
                <input type="file" name="logo" id="logo" accept="image/*"
                  class="flex-1 px-4 py-2 rounded-lg border border-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition">
              </div>
              <p class="mt-1 text-xs text-gray-500">Max size 2MB (JPG, PNG). Leave empty to keep current.</p>
              @error('logo') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="col-span-2">
              <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">About Company</label>
              <textarea name="description" id="description" rows="5" required
                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                placeholder="Describe what your company does...">{{ old('description', $company->description) }}</textarea>
              @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
          </div>

          <div class="flex items-center justify-end gap-4">
            <a href="{{ route('companies.index') }}"
              class="px-6 py-3 text-gray-700 font-semibold rounded-lg hover:bg-gray-100 transition">
              Cancel
            </a>
            <button type="submit"
              class="px-8 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition shadow-lg shadow-blue-200">
              Update Company
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection