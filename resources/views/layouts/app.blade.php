<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Jobs to Find - Search for a job!')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen">
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="/" class="text-2xl font-bold text-blue-600 hover:text-blue-700 transition">
                        Jobs to Find
                    </a>
                </div>

                <div class="flex items-center">
                    @auth
                        <a href="/" class="transition p-2 rounded-lg {{ request()->is('/') ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50' }}" title="Home">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </a>
                        
                        @if(Auth::user()->account_type === 'employer')
                            <a href="{{ route('offer.create') }}" class="text-gray-600 hover:text-green-600 transition p-2 rounded-lg hover:bg-green-50" title="Add New Job Offer">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                            </a>
                        @endif
                        
                        @if(Auth::user()->account_type === 'admin')
                            @php
                                $pendingCount = \App\Models\JobOffer::where('is_approved', false)->where('is_active', true)->count();
                            @endphp
                            
                            <a href="/admin/accept-offers" class="text-gray-600 hover:text-green-600 transition p-2 rounded-lg hover:bg-green-50 relative" title="Accept Offers">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                @if($pendingCount > 0)
                                    <span class="absolute -top-1 -right-1 bg-red-600 text-white text-xs font-bold rounded-full min-w-[20px] h-5 flex items-center justify-center px-1">
                                        {{ $pendingCount > 99 ? '+99' : $pendingCount }}
                                    </span>
                                @endif
                            </a>
                            
                            <a href="/admin/statistics" class="text-gray-600 hover:text-purple-600 transition p-2 rounded-lg hover:bg-purple-50" title="Statistics">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </a>
                            
                            <a href="/admin/reports" class="text-gray-600 hover:text-red-600 transition p-2 rounded-lg hover:bg-red-50" title="Reports">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </a>
                        @else
                            <button class="text-gray-600 hover:text-blue-600 transition p-2 rounded-lg hover:bg-blue-50 relative cursor-pointer" title="Notifications">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                            </button>
                        @endif

                        <div class="relative group">
                            <button class="text-gray-700 px-4 py-2 text-sm font-medium hover:text-blue-600 transition flex items-center cursor-pointer">
                                @if(Auth::user()->account_type === 'admin')
                                    <span class="text-blue-600 font-semibold">Administrator</span>
                                @else
                                    <span class="text-gray-700">{{ Auth::user()->first_name }}</span>
                                    <span class="text-blue-600 ml-1">{{ Auth::user()->last_name }}</span>
                                @endif
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                <div class="py-2">
                                    @if(Auth::user()->account_type === 'admin')
                                        <a href="/admin/offers" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                                            Offers
                                        </a>
                                        <a href="/admin/users" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                                            Users
                                        </a>
                                        <a href="/admin/statistics" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                                            Statistics
                                        </a>
                                        <a href="/admin/accept-offers" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                                            Accept offers
                                        </a>
                                        <a href="/admin/reports" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                                            Reports
                                        </a>
                                    @else
                                        <a href="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                                            Profile
                                        </a>
                                        @if(Auth::user()->account_type === 'job_seeker')
                                            <a href="/my-applications" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                                                My applications
                                            </a>
                                        @else
                                            <a href="/my-offers" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                                                My job offers
                                            </a>
                                        @endif
                                    @endif
                                    <div class="border-t border-gray-200 my-1"></div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 transition cursor-pointer">
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="/login" class="text-gray-700 hover:text-blue-600 px-4 py-2 rounded-md text-sm font-medium transition">
                            Login
                        </a>
                        <a href="/register" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition shadow-sm">
                            Register
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <p class="text-center text-gray-500 text-sm">
                &copy; {{ date('Y') }} Jobs to Find.
            </p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
