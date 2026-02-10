@extends('layouts.app')

@section('title', 'Terms of Service - Jobs to Find')

@section('content')
<div class="min-h-[calc(100vh-8rem)] py-12 px-4">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-8 md:p-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-6">
                Terms of Service
            </h1>
            
            <div class="prose max-w-none">
                <p class="text-gray-600 mb-8">
                    Last updated: {{ date('F d, Y') }}
                </p>

                <div class="space-y-6 text-gray-700">
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">1. ...</h2>
                        <p>
                            TOS here.
                        </p>
                    </section>
                </div>
                <div class="mt-12 p-6 bg-gray-50 rounded-lg border border-gray-200">
                    <p class="text-sm text-gray-600">
                        By continuing to use Jobs to Find, you acknowledge that you have read and understood these Terms of Service and agree to be bound by them.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
