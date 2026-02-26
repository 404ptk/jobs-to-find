@extends('layouts.app')

@section('title', 'Offers Management - Admin Panel')

@section('content')
    <div class="min-h-[calc(100vh-8rem)] py-12 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Offers Management</h1>
                <p class="text-gray-600 mt-1">Manage all job offers in the system.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <x-stat-card title="Total Offers" :value="$totalOffers" color="blue">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                        </path>
                    </svg>
                </x-stat-card>

                <x-stat-card title="Active Offers" :value="$activeOffers" color="green">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </x-stat-card>

                <x-stat-card title="Pending" :value="$pendingOffers" color="yellow">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </x-stat-card>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
                <x-toolbar :total="$offers->total()" routeName="admin.offers" gridId="offersTable"
                    :showGridButtons="false" />
                <form action="{{ route('admin.offers') }}" method="GET" class="flex flex-col md:flex-row gap-4 mt-4">
                    <div class="flex-1">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search by title or company..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
                        Search
                    </button>
                    @if(request('search'))
                        <a href="{{ route('admin.offers') }}"
                            class="px-6 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition text-center">
                            Clear
                        </a>
                    @endif
                </form>
            </div>

            <x-table :headers="['Job Offer', 'Posted By', 'Status', 'Date', 'Actions']" :pagination="$offers">
                @forelse($offers as $offer)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $offer->title }}</div>
                                    <div class="text-sm text-gray-500">{{ $offer->company_name }}</div>
                                    <div class="text-xs text-gray-400">{{ $offer->location->city }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $offer->user->first_name }} {{ $offer->user->last_name }}
                            </div>
                            <div class="text-xs text-gray-500">{{ $offer->user->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($offer->is_active && $offer->is_approved)
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                            @elseif($offer->is_active && !$offer->is_approved)
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Pending
                                </span>
                            @else
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    Inactive
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $offer->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('job.show', $offer->id) }}" class="text-blue-600 hover:text-blue-900">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            No job offers found matching your search.
                        </td>
                    </tr>
                @endforelse
            </x-table>
        </div>
    </div>
@endsection