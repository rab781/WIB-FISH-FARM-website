@extends('admin.layouts.app')
@section('title', 'Profil Admin')

@section('content')
<div class="py-6 px-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold mb-1">{{ $title }}</h1>
        <p class="text-gray-500">Kelola informasi profil anda</p>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
        <p class="font-medium">{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
        <p class="font-medium">{{ session('error') }}</p>
    </div>
    @endif

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-6 md:p-8 bg-gradient-to-r from-yellow-600 to-orange-500">
            <div class="flex flex-col md:flex-row items-center">
                <div class="flex-shrink-0 mb-4 md:mb-0 md:mr-6">
                    <div class="relative w-32 h-32 rounded-full overflow-hidden border-4 border-white shadow-md">
                        @if($user->foto && file_exists(public_path('uploads/users/'.$user->foto)))
                            <img src="{{ asset('uploads/users/'.$user->foto) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gray-200 text-gray-500">
                                <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="text-center md:text-left">
                    <h2 class="text-2xl font-bold text-white">{{ $user->name }}</h2>
                    <p class="text-yellow-100 mt-1">Administrator</p>
                    <div class="mt-4">
                        <a href="{{ route('admin.profile.edit') }}" class="inline-flex items-center px-4 py-2 bg-white text-yellow-600 rounded-md hover:bg-yellow-50 transition-colors shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                            Edit Profil
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Kontak</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Email</p>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                </svg>
                                <p class="font-medium">{{ $user->email }}</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Nomor HP</p>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                                </svg>
                                <p class="font-medium">{{ $user->no_hp ?: 'Belum diisi' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Akun</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Status</p>
                            <div class="flex items-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="w-4 h-4 mr-1 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Aktif
                                </span>
                            </div>
                        </div>
                        {{-- <div>
                            <p class="text-sm text-gray-500 mb-1">Bergabung Sejak</p>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                </svg>
                                <p class="font-medium">{{ $user->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Terakhir Login</p>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                                <p class="font-medium">{{ $user->updated_at->diffForHumans() }}</p>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
