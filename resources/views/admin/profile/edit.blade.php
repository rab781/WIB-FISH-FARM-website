@extends('admin.layouts.app')


@section('content')
<div class="py-6 px-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold mb-1">{{ $title }}</h1>
        <p class="text-gray-500">Perbarui informasi profil dan keamanan akun administrator</p>
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

    <form id="profileUpdateForm" action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded-lg overflow-hidden">
        @csrf
        @method('PUT')

        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-5">Informasi Profil</h3>

            <!-- Profile Photo -->
            <div class="mb-8">
                <label class="block text-sm font-medium text-gray-700 mb-2">Foto Profil</label>
                <div class="flex items-center">
                    <div class="w-24 h-24 rounded-full overflow-hidden border border-gray-300 mr-4">
                        @if($user->foto)
                        <img src="{{ asset('uploads/users/'.$user->foto) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                        @else
                        <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-400">
                            <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-yellow-300 focus:border-yellow-500 @error('name') border-red-300 @enderror">
                    @error('name')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-yellow-300 focus:border-yellow-500 @error('email') border-red-300 @enderror">
                    @error('email')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone Number -->
                <div>
                    <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-1">Nomor HP</label>
                    <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp', $user->no_hp) }}" required class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-yellow-300 focus:border-yellow-500 @error('no_hp') border-red-300 @enderror">
                    @error('no_hp')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Password Section -->
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-5">Keamanan Akun</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                    <input type="password" name="password" id="password" class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-yellow-300 focus:border-yellow-500 @error('password') border-red-300 @enderror">
                    <p class="mt-1 text-xs text-gray-500">Biarkan kosong jika tidak ingin mengubah password.</p>
                    @error('password')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-yellow-300 focus:border-yellow-500">
                </div>
            </div>

            <div class="mt-6 bg-yellow-50 p-4 rounded-md border border-yellow-200">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Rekomendasi Keamanan</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>Gunakan password yang kuat dengan kombinasi huruf (kapital & kecil), angka, dan simbol.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Button Section -->
        <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-3">
            <a href="{{ route('admin.profile.show') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                Batal
            </a>
            <button type="submit" class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function showPreview(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                const preview = input.parentElement.previousElementSibling;
                preview.innerHTML = '<img src="' + e.target.result + '" class="w-full h-full object-cover">';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function validatePasswordBeforeSave() {
        const password = document.getElementById('password').value;
        if (!password) {
            Swal.fire({
                title: 'Password Diperlukan',
                text: 'Masukkan password untuk menyimpan perubahan.',
                icon: 'warning',
                confirmButtonColor: '#f59e0b',
                confirmButtonText: 'Mengerti',
                customClass: {
                    popup: 'rounded-lg',
                    confirmButton: 'rounded-md'
                }
            });
            return false;
        }
        return true;
    }

    // Handle form submission with SweetAlert2 confirmation
    document.getElementById('profileUpdateForm').addEventListener('submit', function(e) {
        e.preventDefault();

        Swal.fire({
            title: 'Konfirmasi Perubahan',
            text: 'Apakah Anda yakin ingin menyimpan perubahan data profil?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#f59e0b',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Simpan!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-lg',
                confirmButton: 'rounded-md',
                cancelButton: 'rounded-md'
            },
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return new Promise((resolve) => {
                    // Add a small delay for better UX
                    setTimeout(() => {
                        resolve();
                    }, 500);
                });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading state
                Swal.fire({
                    title: 'Menyimpan...',
                    text: 'Sedang memproses perubahan profil.',
                    icon: 'info',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Submit the form
                this.submit();
            }
        });
    });
</script>
@endpush

@endsection
