@extends('layouts.app')

@section('title', 'Statistics - Admin Panel')

@section('content')
  <div class="min-h-[calc(100vh-8rem)] py-12 px-4">
    <div class="max-w-7xl mx-auto">
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Statistics</h1>
        <p class="text-gray-600 mt-1">Month-by-month overview of platform activity.</p>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <x-stat-card title="Total Offers" :value="$totalOffers" color="blue">
          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
            </path>
          </svg>
        </x-stat-card>

        <x-stat-card title="Total Users" :value="$totalUsers" color="green">
          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
            </path>
          </svg>
        </x-stat-card>

        <x-stat-card title="Total Applications" :value="$totalApplications" color="yellow">
          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
            </path>
          </svg>
        </x-stat-card>

        <x-stat-card title="Offers This Month" :value="$thisMonthOffers" color="purple">
          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
            </path>
          </svg>
        </x-stat-card>

        <x-stat-card title="Users This Month" :value="$thisMonthUsers" color="orange">
          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
          </svg>
        </x-stat-card>

        <x-stat-card title="Apps This Month" :value="$thisMonthApplications" color="pink">
          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
          </svg>
        </x-stat-card>
      </div>

      <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
          <div>
            <h2 class="text-xl font-semibold text-gray-900">Activity Trends</h2>
            <p class="text-sm text-gray-500 font-medium">Monthly comparison across key metrics</p>
          </div>

          <div class="flex items-center bg-gray-50 rounded-2xl p-1.5 border border-gray-200">
            <button type="button" id="prevRange"
              class="cursor-pointer p-2.5 hover:bg-white hover:text-blue-600 hover:shadow-md rounded-xl transition-all disabled:opacity-30 disabled:hover:bg-transparent">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
              </svg>
            </button>
            <span id="rangeLabel" class="px-6 text-sm font-semibold text-gray-600 min-w-[150px] text-center">... -
              ...</span>
            <button type="button" id="nextRange"
              class="cursor-pointer p-2.5 hover:bg-white hover:text-blue-600 hover:shadow-md rounded-xl transition-all disabled:opacity-30 disabled:hover:bg-transparent">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
              </svg>
            </button>
          </div>
        </div>

        <div class="relative" style="height: 450px;">
          <canvas id="statisticsChart"></canvas>
        </div>
      </div>
    </div>
  </div>

  @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        const fullLabels = @json($labels);
        const fullOffers = @json($offersData);
        const fullUsers = @json($usersData);
        const fullApps = @json($applicationsData);

        let windowSize = 3;
        let startIndex = Math.max(0, fullLabels.length - windowSize);

        const ctx = document.getElementById('statisticsChart').getContext('2d');
        let chart = null;

        function updateChart() {
          const labels = fullLabels.slice(startIndex, startIndex + windowSize);
          const offers = fullOffers.slice(startIndex, startIndex + windowSize);
          const users = fullUsers.slice(startIndex, startIndex + windowSize);
          const apps = fullApps.slice(startIndex, startIndex + windowSize);

          document.getElementById('rangeLabel').textContent = `${labels[0]} - ${labels[labels.length - 1]}`;

          document.getElementById('prevRange').disabled = startIndex === 0;
          document.getElementById('nextRange').disabled = startIndex >= fullLabels.length - windowSize;

          if (chart) {
            chart.data.labels = labels;
            chart.data.datasets[0].data = offers;
            chart.data.datasets[1].data = users;
            chart.data.datasets[2].data = apps;
            chart.update('active');
          } else {
            chart = new Chart(ctx, {
              type: 'bar',
              data: {
                labels: labels,
                datasets: [
                  {
                    label: 'Job Offers',
                    data: offers,
                    backgroundColor: 'rgba(59, 130, 246, 0.7)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1.5,
                    borderRadius: 8,
                    barPercentage: 0.6,
                    categoryPercentage: 0.7
                  },
                  {
                    label: 'New Users',
                    data: users,
                    backgroundColor: 'rgba(34, 197, 94, 0.7)',
                    borderColor: 'rgba(34, 197, 94, 1)',
                    borderWidth: 1.5,
                    borderRadius: 8,
                    barPercentage: 0.6,
                    categoryPercentage: 0.7
                  },
                  {
                    label: 'Applications',
                    data: apps,
                    backgroundColor: 'rgba(236, 72, 153, 0.7)',
                    borderColor: 'rgba(236, 72, 153, 1)',
                    borderWidth: 1.5,
                    borderRadius: 8,
                    barPercentage: 0.6,
                    categoryPercentage: 0.7
                  }
                ]
              },
              options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { intersect: false, mode: 'index' },
                plugins: {
                  legend: {
                    position: 'top',
                    labels: {
                      usePointStyle: true,
                      padding: 20,
                      font: { size: 13, weight: '500' }
                    }
                  },
                  tooltip: {
                    backgroundColor: 'rgba(17, 24, 39, 0.9)',
                    titleFont: { size: 13 },
                    bodyFont: { size: 12 },
                    padding: 12,
                    cornerRadius: 8
                  }
                },
                scales: {
                  y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1, font: { size: 12 }, color: '#6B7280' },
                    grid: { color: 'rgba(229, 231, 235, 0.5)' }
                  },
                  x: {
                    ticks: { font: { size: 11 }, color: '#6B7280', maxRotation: 45, minRotation: 0 },
                    grid: { display: false }
                  }
                }
              }
            });
          }
        }

        document.getElementById('prevRange').addEventListener('click', () => {
          if (startIndex > 0) {
            startIndex--;
            updateChart();
          }
        });

        document.getElementById('nextRange').addEventListener('click', () => {
          if (startIndex < fullLabels.length - windowSize) {
            startIndex++;
            updateChart();
          }
        });

        updateChart();
      });
    </script>
  @endpush
@endsection