@extends('layouts.app')

@section('title', 'Home - Jobs to Find')

@section('content')
<div id="mouse-bg" class="min-h-[calc(100vh-8rem)] flex items-center justify-center px-4 relative overflow-hidden">
    <div id="radial-gradient" class="absolute inset-0 pointer-events-none transition-opacity duration-300" style="background: radial-gradient(600px circle at 50% 50%, rgba(0, 0, 0, 0.15), transparent 80%);"></div>
    
    <div class="w-full max-w-4xl relative z-10">
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                Find your dream job
            </h1>
            <p class="text-lg text-gray-600">
                Browse thousands of job offers and find the perfect position for you
            </p>
        </div>

        <div class="bg-white rounded-lg shadow-xl p-6 md:p-8">
            <form class="space-y-4">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                            Position or keyword
                        </label>
                        <input 
                            type="text" 
                            id="search" 
                            name="search" 
                            placeholder="e.g. Frontend Developer, Marketing Manager..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        >
                    </div>
                    
                    <div class="flex-1">
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                            Location
                        </label>
                        <input 
                            type="text" 
                            id="location" 
                            name="location" 
                            placeholder="e.g. Warsaw, Krakow..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        >
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                            Category
                        </label>
                        <select 
                            id="category" 
                            name="category"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        >
                            <option value="">All categories</option>
                            <option value="it">IT / Programming</option>
                            <option value="marketing">Marketing</option>
                            <option value="hr">HR / Recruitment</option>
                            <option value="sales">Sales</option>
                            <option value="finance">Finance</option>
                        </select>
                    </div>

                    <div class="flex-1">
                        <label for="employment_type" class="block text-sm font-medium text-gray-700 mb-2">
                            Employment type
                        </label>
                        <select 
                            id="employment_type" 
                            name="employment_type"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        >
                            <option value="">All types</option>
                            <option value="full-time">Full-time</option>
                            <option value="part-time">Part-time</option>
                            <option value="contract">Contract / B2B</option>
                            <option value="internship">Internship</option>
                        </select>
                    </div>
                </div>

                <div class="pt-2">
                    <button 
                        type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-4 px-6 rounded-lg transition shadow-md hover:shadow-lg flex items-center justify-center gap-2"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Search jobs
                    </button>
                </div>
            </form>
        </div>

        <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="text-3xl font-bold text-blue-600 mb-2">1000+</div>
                <div class="text-gray-600">Active jobs</div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="text-3xl font-bold text-blue-600 mb-2">500+</div>
                <div class="text-gray-600">Companies</div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="text-3xl font-bold text-blue-600 mb-2">50+</div>
                <div class="text-gray-600">Categories</div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('mouse-bg');
    const gradient = document.getElementById('radial-gradient');
    
    container.addEventListener('mousemove', function(e) {
        const rect = container.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        
        gradient.style.background = `radial-gradient(600px circle at ${x}px ${y}px, rgba(0, 0, 0, 0.15), transparent 80%)`;
    });
    
    container.addEventListener('mouseleave', function() {
        gradient.style.opacity = '0';
    });
    
    container.addEventListener('mouseenter', function() {
        gradient.style.opacity = '1';
    });
});
</script>
@endsection
