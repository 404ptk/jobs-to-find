@extends('layouts.app')

@section('title', 'Statistics - Admin Panel')

@section('content')
  <div class="min-h-[calc(100vh-8rem)] py-12 px-4">
    <div class="max-w-7xl mx-auto">
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Statistics</h1>
        <p class="text-gray-600 mt-1">Month-by-month overview of platform activity.</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-blue-500">
          <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
              <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                </path>
              </svg>
            </div>
            <div>
              <p class="text-sm text-gray-500 font-medium uppercase tracking-wider">Total Offers</p>
              <p class="text-2xl font-bold text-gray-900">{{ $totalOffers }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-green-500">
          <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
              <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                </path>
              </svg>
            </div>
            <div>
              <p class="text-sm text-gray-500 font-medium uppercase tracking-wider">Total Users</p>
              <p class="text-2xl font-bold text-gray-900">{{ $totalUsers }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-purple-500">
          <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
              <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                </path>
              </svg>
            </div>
            <div>
              <p class="text-sm text-gray-500 font-medium uppercase tracking-wider">Offers This Month</p>
              <p class="text-2xl font-bold text-gray-900">{{ $thisMonthOffers }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-orange-500">
          <div class="flex items-center">
            <div class="p-3 rounded-full bg-orange-100 text-orange-600 mr-4">
              <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
              </svg>
            </div>
            <div>
              <p class="text-sm text-gray-500 font-medium uppercase tracking-wider">Users This Month</p>
              <p class="text-2xl font-bold text-gray-900">{{ $thisMonthUsers }}</p>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-6">Monthly Comparison – Job Offers vs New Users</h2>
        <div class="relative" style="height: 400px;">
          <canvas id="statisticsChart"></canvas>
        </div>
      </div>
    </div>
  </div>

  @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <script>   document.addEventListener('DOMContentLoaded', function () {     const ctx = document.getElementById('statisticsChart').getContext('2d');
         new Chart(ctx, {       type: 'bar',       data: {         labels: @json($labels),         datasets: [           {             label: 'Job Offers',             data: @json($offersData),             backgroundColor: 'rgba(59, 130, 246, 0.7)',             borderColor: 'rgba(59, 130, 246, 1)',             borderWidth: 1,             borderRadius: 4,             barPercentage: 0.6,             categoryPercentage: 0.7           },           {             label: 'New Users',             data: @json($usersData),             backgroundColor: 'rgba(34, 197, 94, 0.7)',             borderColor: 'rgba(34, 197, 94, 1)',             borderWidth: 1,             borderRadius: 4,             barPercentage: 0.6,             categoryPercentage: 0.7           }         ]       },       options: {         responsive: true,         maintainAspectRatio: false,         interaction: {           intersect: false,           mode: 'index'         },         plugins: {           legend: {             position: 'top',             labels: {               usePointStyle: true,               padding: 20,               font: { size: 13, weight: '500' }             }           },           tooltip: {             backgroundColor: 'rgba(17, 24, 39, 0.9)',             titleFont: { size: 13 },             bodyFont: { size: 12 },             padding: 12,             cornerRadius: 8           }         },         scales: {           y: {             beginAtZero: true,             ticks: {               stepSize: 1,               font: { size: 12 },               color: '#6B7280'             },             grid: {               color: 'rgba(229, 231, 235, 0.5)'             }           },           x: {             ticks: {               font: { size: 11 },               color: '#6B7280',               maxRotation: 45,               minRotation: 0             },             grid: {               display: false             }           }         }       }     });   });
    </script>
  @endpush
@endsection