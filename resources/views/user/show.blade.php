@extends('layouts.app')

@section('title', $user->first_name . ' ' . $user->last_name . ' - Profile')

@section('head')
    @if(Auth::id() === $user->id)
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />
    <style>
        .cropper-view-box,
        .cropper-face {
            border-radius: 50%;
        }
    </style>
    @endif
@endsection

@section('content')
<div class="min-h-[calc(100vh-8rem)] py-12 px-4">
    <div class="max-w-4xl mx-auto">
        @if(Auth::id() === $user->id)
            <div class="mb-6 bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                This is a preview of how your profile appears to employers.
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Header/Cover -->
            <div class="h-32 bg-gradient-to-r from-blue-500 to-indigo-600"></div>
            
            <div class="px-8 pb-8">
                <div class="relative flex items-end -mt-16 mb-6">
                    <div class="relative">
                        <div class="w-32 h-32 rounded-full border-4 border-white overflow-hidden bg-white shadow-md group relative">
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->username }}" class="w-full h-full object-cover">
                            @else
                                <img src="{{ asset('images/default-avatar.svg') }}" alt="Default Avatar" class="w-full h-full object-cover">
                            @endif

                            @if(Auth::id() === $user->id)
                                <label for="avatar" class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-200 cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </label>
                                <form action="{{ route('profile.update') }}" method="POST" id="avatarForm" enctype="multipart/form-data" class="hidden">
                                    @csrf
                                    @method('PUT')
                                    <input type="file" id="avatar" name="avatar" accept="image/*" class="hidden">
                                    <input type="hidden" name="first_name" value="{{ $user->first_name }}">
                                    <input type="hidden" name="last_name" value="{{ $user->last_name }}">
                                    <input type="hidden" name="country" value="{{ $user->country }}">
                                </form>
                            @endif
                        </div>
                    </div>
                    <div class="ml-6 mb-2 flex-1">
                        <h1 class="text-3xl font-bold text-gray-900">{{ $user->first_name }} {{ $user->last_name }}</h1>
                        <p class="text-gray-600 font-medium">{{ '@' . $user->username }}</p>
                    </div>
                    @if(Auth::id() === $user->id)
                        <div class="mb-4">
                            <a href="{{ route('profile') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                                Edit Profile
                            </a>
                        </div>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Sidebar info -->
                    <div class="space-y-6">
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Details</h3>
                            
                            <div class="space-y-4">
                                <div class="flex items-center text-gray-700">
                                    <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>{{ $user->country }}</span>
                                </div>
                                
                                <div class="flex items-center text-gray-700">
                                    <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span>Joined {{ $user->created_at->format('F Y') }}</span>
                                </div>

                                @if($user->date_of_birth)
                                    <div class="flex items-center text-gray-700">
                                        <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.701 2.701 0 00-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7h18zm-3-9v-9a2 2 0 00-2-2H8a2 2 0 00-2 2v9h12z" />
                                        </svg>
                                        <span>{{ \Carbon\Carbon::parse($user->date_of_birth)->age }} years old</span>
                                    </div>
                                @endif

                                @if($user->account_type === 'job_seeker' && $user->is_student)
                                    <div class="flex items-center text-gray-700">
                                        <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 14l9-5-9-5-9 5 9 5z" />
                                            <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                        </svg>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            Student
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if($user->cv_path)
                            <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                                <h3 class="text-sm font-semibold text-blue-800 uppercase tracking-wider mb-3">Resume</h3>
                                <a href="{{ asset('storage/' . $user->cv_path) }}" target="_blank" class="flex items-center justify-center w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Download CV
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Main content -->
                    <div class="md:col-span-2 space-y-8">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                About
                            </h2>
                            <div class="prose max-w-none text-gray-600">
                                <p>
                                    {{ $user->first_name }} hasn't added a bio yet.
                                </p>
                            </div>
                        </div>

                        <!-- We can add more sections here like Experience, Education etc in the future -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
@if(Auth::id() === $user->id)
<div id="avatar-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-white/10 backdrop-blur-md transition-opacity" aria-hidden="true"></div>

    <div class="relative bg-white rounded-xl shadow-2xl max-w-lg w-full overflow-hidden transform transition-all scale-100">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-900" id="modal-title">
                Adjust Profile Picture
            </h3>
            <button type="button" id="close-modal-x" class="text-gray-400 hover:text-gray-500 cursor-pointer">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="p-6">
            <div class="relative w-full h-[400px] bg-slate-100 rounded-lg overflow-hidden border border-gray-200">
                <img id="avatar-image-preview" class="max-w-full" src="">
            </div>
            
            <div class="mt-4 flex items-center justify-center space-x-4">
                <button type="button" id="zoom-out" class="p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-full transition cursor-pointer" title="Zoom Out">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM13 10H7" />
                    </svg>
                </button>
                <button type="button" id="zoom-in" class="p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-full transition cursor-pointer" title="Zoom In">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                    </svg>
                </button>
                <div class="w-px h-6 bg-gray-300 mx-2"></div>
                <button type="button" id="rotate-left" class="p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-full transition cursor-pointer" title="Rotate Left">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                    </svg>
                </button>
                <button type="button" id="rotate-right" class="p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-full transition cursor-pointer" title="Rotate Right">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 10h-10a8 8 0 00-8 8v2M21 10l-6 6m6-6l-6-6" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3 border-t border-gray-100">
            <button type="button" id="cancel-crop" class="px-4 py-2 bg-white text-gray-700 font-medium rounded-lg border border-gray-300 hover:bg-gray-50 transition shadow-sm cursor-pointer">
                Cancel
            </button>
            <button type="button" id="crop-button" class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition shadow-sm flex items-center cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Save Avatar
            </button>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
    const avatarInput = document.getElementById('avatar');
    const avatarModal = document.getElementById('avatar-modal');
    const avatarImage = document.getElementById('avatar-image-preview');
    let cropper = null;

    function closeAvatarModal() {
        avatarModal.classList.add('hidden');
        avatarInput.value = '';
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
    }

    avatarInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            if (file.size > 2 * 1024 * 1024) {
                alert('File size exceeds 2MB limit.');
                this.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                avatarImage.src = e.target.result;
                avatarModal.classList.remove('hidden');
                
                if (cropper) {
                    cropper.destroy();
                }

                cropper = new Cropper(avatarImage, {
                    aspectRatio: 1,
                    viewMode: 1,
                    dragMode: 'move',
                    autoCropArea: 0.8,
                    restore: false,
                    guides: true,
                    center: true,
                    highlight: false,
                    cropBoxMovable: true,
                    cropBoxResizable: true,
                    toggleDragModeOnDblclick: false,
                    background: false,
                });
            };
            reader.readAsDataURL(file);
        }
    });

    document.getElementById('zoom-in').addEventListener('click', function() {
        if(cropper) cropper.zoom(0.1);
    });
    
    document.getElementById('zoom-out').addEventListener('click', function() {
        if(cropper) cropper.zoom(-0.1);
    });

    document.getElementById('rotate-left').addEventListener('click', function() {
        if(cropper) cropper.rotate(-90);
    });
    
    document.getElementById('rotate-right').addEventListener('click', function() {
        if(cropper) cropper.rotate(90);
    });

    document.getElementById('cancel-crop').addEventListener('click', closeAvatarModal);
    document.getElementById('close-modal-x').addEventListener('click', closeAvatarModal);

    document.getElementById('crop-button').addEventListener('click', function() {
        if (!cropper) return;

        const canvas = cropper.getCroppedCanvas({
            width: 400,
            height: 400,
        });

        canvas.toBlob(function(blob) {
            const file = new File([blob], "avatar.jpg", { type: "image/jpeg" });
            
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            avatarInput.files = dataTransfer.files;

            avatarModal.classList.add('hidden');
            
            setTimeout(() => {
                document.getElementById('avatarForm').submit();
            }, 100);

        }, 'image/jpeg', 0.9);
    });
</script>
@endif
@endpush
@endsection
