@extends('layouts.app')

@section('title', 'Register - Jobs to Find')

@section('content')
<div id="mouse-bg" class="min-h-[calc(100vh-8rem)] flex items-center justify-center px-4 py-8 relative overflow-hidden">
    <div id="radial-gradient" class="absolute inset-0 pointer-events-none transition-opacity duration-300" style="background: radial-gradient(600px circle at 50% 50%, rgba(0, 0, 0, 0.15), transparent 80%);"></div>
    
    <div class="w-full max-w-md relative z-10">
        <div class="text-center mb-8">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">
                Join us!
            </h1>
            <p class="text-gray-600">
                Create your account and start finding your dream job today
            </p>
        </div>

        <div class="bg-white rounded-lg shadow-xl p-8">
            <form class="space-y-5">
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                        Nickname
                    </label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        placeholder="yournickname"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        required
                    >
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Name
                        </label>
                        <input 
                            type="text" 
                            id="first_name" 
                            name="first_name" 
                            placeholder="John"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            required
                        >
                    </div>
                    
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Last name
                        </label>
                        <input 
                            type="text" 
                            id="last_name" 
                            name="last_name" 
                            placeholder="Doe"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            required
                        >
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Mail address
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        placeholder="your@mail.com"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        required
                    >
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password
                    </label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="••••••••"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        required
                    >
                    <p class="mt-1 text-sm text-gray-500">Minimum 8 characters</p>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        Confirm password
                    </label>
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        placeholder="••••••••"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        required
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        Account type
                    </label>
                    <div class="grid grid-cols-2 gap-4">
                        <button 
                            type="button"
                            id="account-job-seeker"
                            onclick="selectAccountType('job_seeker')"
                            class="account-type-btn px-4 py-3 border-2 border-blue-600 bg-blue-600 text-white rounded-lg font-medium transition hover:bg-blue-700 hover:border-blue-700"
                        >
                            Looking for a job
                        </button>
                        <button 
                            type="button"
                            id="account-employer"
                            onclick="selectAccountType('employer')"
                            class="account-type-btn px-4 py-3 border-2 border-gray-300 bg-white text-gray-700 rounded-lg font-medium transition hover:border-gray-400"
                        >
                            I'm an employer
                        </button>
                    </div>
                    <input type="hidden" id="account_type" name="account_type" value="job_seeker" required>
                </div>

                <div class="flex items-start">
                    <input 
                        type="checkbox" 
                        id="terms" 
                        name="terms"
                        class="h-4 w-4 mt-1 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                        required
                    >
                    <label for="terms" class="ml-2 block text-sm text-gray-700">
                        Accept
                        <a href="/tos" class="text-blue-600 hover:text-blue-700 transition">Terms of Service</a>
                        and
                        <a href="/privacy" class="text-blue-600 hover:text-blue-700 transition">Privacy Policy</a>
                    </label>
                </div>

                <button 
                    type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition shadow-md hover:shadow-lg"
                >
                    Create account
                </button>

                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">Or continue with</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <button 
                        type="button"
                        class="flex items-center justify-center px-4 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition"
                    >
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                        </svg>
                        <span class="ml-2 text-sm font-medium text-gray-700">Google</span>
                    </button>

                    <button 
                        type="button"
                        class="flex items-center justify-center px-4 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition"
                    >
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.477 2 2 6.477 2 12c0 4.42 2.865 8.17 6.839 9.49.5.092.682-.217.682-.482 0-.237-.008-.866-.013-1.7-2.782.603-3.369-1.34-3.369-1.34-.454-1.156-1.11-1.463-1.11-1.463-.908-.62.069-.608.069-.608 1.003.07 1.531 1.03 1.531 1.03.892 1.529 2.341 1.087 2.91.831.092-.646.35-1.086.636-1.336-2.22-.253-4.555-1.11-4.555-4.943 0-1.091.39-1.984 1.029-2.683-.103-.253-.446-1.27.098-2.647 0 0 .84-.269 2.75 1.025A9.578 9.578 0 0112 6.836c.85.004 1.705.114 2.504.336 1.909-1.294 2.747-1.025 2.747-1.025.546 1.377.203 2.394.1 2.647.64.699 1.028 1.592 1.028 2.683 0 3.842-2.339 4.687-4.566 4.935.359.309.678.919.678 1.852 0 1.336-.012 2.415-.012 2.743 0 .267.18.578.688.48C19.138 20.167 22 16.418 22 12c0-5.523-4.477-10-10-10z"/>
                        </svg>
                        <span class="ml-2 text-sm font-medium text-gray-700">GitHub</span>
                    </button>
                </div>
            </form>
        </div>

        <p class="text-center mt-6 text-gray-600">
            Already have an account?
            <a href="/login" class="text-blue-600 hover:text-blue-700 font-medium transition">
                Log in
            </a>
        </p>
    </div>
</div>

<script>
function selectAccountType(type) {
    const jobSeekerBtn = document.getElementById('account-job-seeker');
    const employerBtn = document.getElementById('account-employer');
    const hiddenInput = document.getElementById('account_type');
    
    if (type === 'job_seeker') {
        jobSeekerBtn.className = 'account-type-btn px-4 py-3 border-2 border-blue-600 bg-blue-600 text-white rounded-lg font-medium transition hover:bg-blue-700 hover:border-blue-700';
        employerBtn.className = 'account-type-btn px-4 py-3 border-2 border-gray-300 bg-white text-gray-700 rounded-lg font-medium transition hover:border-gray-400';
        hiddenInput.value = 'job_seeker';
    } else {
        jobSeekerBtn.className = 'account-type-btn px-4 py-3 border-2 border-gray-300 bg-white text-gray-700 rounded-lg font-medium transition hover:border-gray-400';
        employerBtn.className = 'account-type-btn px-4 py-3 border-2 border-blue-600 bg-blue-600 text-white rounded-lg font-medium transition hover:bg-blue-700 hover:border-blue-700';
        hiddenInput.value = 'employer';
    }
}

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
