@extends('layouts.customer')

@section('content')
<div class="bg-white py-8">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Profile Header with Background -->
        <div class="relative bg-gradient-to-r from-orange-500 to-red-600 rounded-lg overflow-hidden shadow-lg mb-6">
            <div class="absolute inset-0 bg-pattern opacity-10"></div>
            <div class="relative px-6 py-8 md:px-8 flex flex-col md:flex-row items-center">
                <!-- Profile Image -->
                <div class="w-28 h-28 rounded-full overflow-hidden border-4 border-white shadow-md mb-4 md:mb-0 md:mr-6 flex-shrink-0">
                    @if($user->foto)
                        <img src="{{ asset('storage/uploads/users/'.$user->foto) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gray-200 text-gray-600">
                            <svg class="w-14 h-14" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    @endif
                </div>

                <!-- Profile Info -->
                <div class="text-center md:text-left">
                    <h1 class="text-2xl font-bold text-white">{{ $user->name }}</h1>
                    <div class="text-orange-100 mt-1">{{ $user->email }}</div>
                    <div class="text-orange-100 mt-1">{{ $user->no_hp }}</div>
                    <div class="mt-4">
                        <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-4 py-2 bg-white text-orange-600 rounded-md hover:bg-orange-50 transition-colors shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit Profil
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-md" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <!-- Profile Details Card -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Informasi Pribadi</h3>
            </div>

            <div class="p-6 bg-white">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                    <div>
                        <div class="text-sm text-gray-500 mb-1">Nama Lengkap</div>
                        <div class="font-medium">{{ $user->name }}</div>
                    </div>

                    <div>
                        <div class="text-sm text-gray-500 mb-1">Email</div>
                        <div class="font-medium">{{ $user->email }}</div>
                    </div>

                    <div>
                        <div class="text-sm text-gray-500 mb-1">Nomor HP</div>
                        <div class="font-medium">{{ $user->no_hp ?: 'Belum diisi' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Address Card -->
        <div class="mt-6 bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Alamat</h3>
            </div>

            <div class="p-6 bg-white">
                @if($user->kecamatan)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                        <div>
                            <div class="text-sm text-gray-500 mb-1">Provinsi</div>
                            <div class="font-medium">{{ $user->getProvinsi()->nama_provinsi ?? 'Belum diisi' }}</div>
                        </div>

                        <div>
                            <div class="text-sm text-gray-500 mb-1">Kabupaten/Kota</div>
                            <div class="font-medium">{{ $user->getKabupaten()->nama_kabupaten ?? 'Belum diisi' }}</div>
                        </div>

                        <div>
                            <div class="text-sm text-gray-500 mb-1">Kecamatan</div>
                            <div class="font-medium">{{ $user->kecamatan->nama ?? 'Belum diisi' }}</div>
                        </div>

                        <div>
                            <div class="text-sm text-gray-500 mb-1">Alamat Jalan</div>
                            <div class="font-medium">{{ $user->alamat_jalan ?: 'Belum diisi' }}</div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada alamat</h3>
                        <p class="mt-1 text-sm text-gray-500">Tambahkan alamat untuk memudahkan proses pengiriman.</p>
                        <div class="mt-6">
                            <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Tambah Alamat
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
