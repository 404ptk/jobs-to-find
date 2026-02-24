@extends('layouts.app')

@section('title', 'Favorites - Jobs to Find')

@section('content')
  <div class="min-h-[calc(100vh-8rem)] py-12 px-4">
    <div class="max-w-7xl mx-auto">
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">My Favorites</h1>
        <p class="text-gray-600 mt-1">Job offers you've saved for later</p>
      </div>

      @if($favorites->isEmpty())
        <div class="bg-white rounded-lg shadow-sm p-12 text-center border border-gray-200">
          <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
          </svg>
          <h3 class="text-xl font-semibold text-gray-900 mb-2">No favorites yet</h3>
          <p class="text-gray-600 mb-6">Start browsing job offers and save the ones you like!</p>
          <a href="{{ route('search') }}"
            class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            Browse Job Offers
          </a>
        </div>
      @else
        <x-toolbar :total="$favorites->total()" :currentPerPage="10" :perPageOptions="[10, 20, 50]" routeName="favorites"
          gridId="favoritesGrid" storageKey="favoritesGridLayout" :defaultColumns="1" />

        <div id="favoritesGrid" class="grid grid-cols-1 gap-4">
          @foreach($favorites as $offer)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition p-6"
              id="fav-card-{{ $offer->id }}">
              <div class="flex items-start justify-between mb-3">
                <div class="flex-1">
                  <a href="{{ route('job.show', $offer->id) }}">
                    <h3 class="text-xl font-semibold text-gray-900 hover:text-blue-600 transition cursor-pointer">
                      {{ $offer->title }}</h3>
                  </a>
                  <p class="text-base text-gray-700 font-medium mt-1">{{ $offer->company_name }}</p>
                </div>
                <button onclick="toggleFavorite({{ $offer->id }}, this)"
                  class="p-2 rounded-full text-red-500 hover:bg-red-50 transition cursor-pointer"
                  title="Remove from favorites">
                  <svg class="w-5 h-5" fill="currentColor" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                  </svg>
                </button>
              </div>

              <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ Str::limit($offer->description, 200) }}</p>

              <div class="flex flex-wrap gap-4 text-sm text-gray-600 mb-4">
                <div class="flex items-center">
                  <svg class="w-4 h-4 mr-1.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                  {{ $offer->location->city }}
                </div>
                <div class="flex items-center">
                  <svg class="w-4 h-4 mr-1.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                  </svg>
                  {{ ucfirst(str_replace('-', ' ', $offer->employment_type)) }}
                </div>
                @if($offer->salary_min || $offer->salary_max)
                  <div class="flex items-center">
                    <svg class="w-4 h-4 mr-1.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    @if($offer->salary_min && $offer->salary_max)
                      {{ number_format($offer->salary_min, 0) }} - {{ number_format($offer->salary_max, 0) }}
                      {{ $offer->currency }}
                    @elseif($offer->salary_min)
                      From {{ number_format($offer->salary_min, 0) }} {{ $offer->currency }}
                    @else
                      Up to {{ number_format($offer->salary_max, 0) }} {{ $offer->currency }}
                    @endif
                  </div>
                @endif
                <div class="flex items-center">
                  <svg class="w-4 h-4 mr-1.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                  </svg>
                  {{ $offer->category->name }}
                </div>
              </div>

              <div class="flex items-center justify-between">
                <span class="text-xs text-gray-500">Saved {{ $offer->pivot->created_at->diffForHumans() }}</span>
                <a href="{{ route('job.show', $offer->id) }}"
                  class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                  View Details
                </a>
              </div>
            </div>
          @endforeach
        </div>

        @if($favorites->hasPages())
          <div class="mt-6">
            {{ $favorites->links() }}
          </div>
        @endif
      @endif
    </div>
  </div>

  <script>
    function toggleFavorite(offerId, button) {
      fetch(`/favorites/toggle/${offerId}`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'Accept': 'application/json',
        }
      })
        .then(r => r.json())
        .then(data => {
          if (!data.favorited) {
            const card = document.getElementById(`fav-card-${offerId}`);
            if (card) {
              card.style.transition = 'opacity 0.3s, transform 0.3s';
              card.style.opacity = '0';
              card.style.transform = 'translateX(-20px)';
              setTimeout(() => card.remove(), 300);
            }
          }
        })
        .catch(err => console.error('Error:', err));
    }
  </script>
@endsection