@extends('layouts.customer')

@section('content')
<div class="bg-gradient-to-b from-gray-50 to-white py-20">
    <div class="container mx-auto px-6">
        <!-- Header Section with improved styling -->
        <div class="mb-16 text-center" data-aos="fade-up">
            <span class="text-orange-500 font-semibold text-sm uppercase tracking-wider mb-2 inline-block">Siapa Kami</span>
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Tentang WIB FISH FARM</h1>
            <div class="w-24 h-1 bg-orange-500 mx-auto mb-6"></div>
            <p class="text-gray-600 max-w-3xl mx-auto text-lg">Tempat penjualan ikan hias berkualitas dari Jember</p>
        </div>

        <!-- Main Content Section with added styling -->
        <div class="flex flex-col lg:flex-row gap-12 mb-20">
            <!-- Left Column: Image with added effects -->
            <div class="w-full lg:w-1/2" data-aos="fade-right">
                <div class="relative">
                    <div class="absolute -top-4 -left-4 w-24 h-24 bg-orange-100 rounded-full -z-10"></div>
                    <img src="{{ asset('Images/kolam-ikan.jpg') }}" alt="Kolam Koi WIB Fish Farm" class="rounded-lg shadow-xl w-full h-auto object-cover relative z-10" onerror="this.src='{{ asset('Images/Default-fish.png') }}'">
                    <div class="absolute -bottom-4 -right-4 w-32 h-32 bg-blue-100 rounded-full -z-10"></div>
                </div>
            </div>

            <!-- Right Column: Description with improved typography -->
            <div class="w-full lg:w-1/2" data-aos="fade-left">
                <span class="text-orange-500 font-medium mb-2 inline-block">Tentang Kami</span>
                <h2 class="text-3xl font-bold text-gray-900 mb-6">WIB FISH FARM</h2>
                <p class="text-gray-700 mb-6 leading-relaxed text-lg">
                    WIB FISH FARM merupakan tempat penjualan ikan hias koi dan koki di daerah Jember, tepatnya di Jalan Danau Toba, Kecamatan Sumbersari, Kabupaten Jember. Ada banyak koleksi ikan koi dan koki yang kami sediakan dengan variasinya. Ikan hias koki dan koi yang kami jual telah teruji kualitasnya.
                </p>
                <p class="text-gray-700 mb-8 leading-relaxed text-lg">
                    Kami melakukan perawatan yang teratur sehingga tumbuh dengan sehat dan indah sehingga beberapa ikan yang tersedia di ikut sertakan dalam kontes.
                </p>
                <p class="text-gray-700 mb-6 leading-relaxed text-lg">
                    Kami juga menyediakan layanan pemesanan ikan secara online untuk memudahkan Anda dalam berbelanja. Dengan pengalaman kami dalam budidaya ikan hias, kami siap memberikan pelayanan terbaik untuk Anda.
                </p>
                <p class="text-gray-700 mb-6 leading-relaxed text-lg">
                    Kami berkomitmen untuk memberikan ikan hias berkualitas dengan harga yang terjangkau. Dengan pengalaman kami dalam budidaya ikan hias, kami siap memberikan pelayanan terbaik untuk Anda.
                </p>
                <div class="flex space-x-4">
                    <span class="px-4 py-2 bg-orange-100 text-orange-700 rounded-lg font-medium inline-flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Kualitas Premium
                    </span>
                    <span class="px-4 py-2 bg-blue-100 text-blue-700 rounded-lg font-medium inline-flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                        Sejak 2020
                    </span>
                </div>
            </div>
        </div>

        <!-- Why Choose Us Section with enhanced design -->
        <div class="py-16 px-6 bg-gray-50 rounded-3xl mb-20" data-aos="fade-up">
            <div class="text-center mb-12">
                <span class="text-orange-500 font-semibold text-sm uppercase tracking-wider mb-2 inline-block">Keunggulan Kami</span>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">MENGAPA KAMI?</h2>
                <div class="w-16 h-1 bg-orange-500 mx-auto mb-4"></div>
                <p class="text-gray-600 max-w-2xl mx-auto">Kami bangga menyediakan ikan hias berkualitas dengan pelayanan terbaik untuk setiap pelanggan</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">
                <div class="flex flex-col md:flex-row gap-6 items-start bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-all duration-300">
                    <div class="bg-orange-100 p-4 rounded-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-xl mb-2 text-gray-900">Ikan dirawat dengan baik dan teratur</h3>
                        <p class="text-gray-700">Setiap ikan mendapatkan perawatan optimal untuk memastikan kesehatan dan keindahan.</p>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-6 items-start bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-all duration-300">
                    <div class="bg-orange-100 p-4 rounded-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-xl mb-2 text-gray-900">Warna dan jenis yang tersedia beragam</h3>
                        <p class="text-gray-700">Koleksi ikan dengan variasi warna dan jenis yang lengkap untuk pilihan Anda.</p>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-6 items-start bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-all duration-300">
                    <div class="bg-orange-100 p-4 rounded-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-xl mb-2 text-gray-900">Harga terjangkau dengan kualitas unggul</h3>
                        <p class="text-gray-700">Menawarkan ikan berkualitas dengan harga yang kompetitif dan terjangkau.</p>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-6 items-start bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-all duration-300">
                    <div class="bg-orange-100 p-4 rounded-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-xl mb-2 text-gray-900">Pemesanan dapat dilakukan secara online</h3>
                        <p class="text-gray-700">Kemudahan berbelanja dari mana saja dengan sistem pemesanan online yang praktis.</p>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-6 items-start md:col-span-2 bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-all duration-300">
                    <div class="bg-orange-100 p-4 rounded-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-xl mb-2 text-gray-900">Pengiriman aman dengan garansi (※ Syarat dan Ketentuan Berlaku)</h3>
                        <p class="text-gray-700">Kami menjamin keamanan ikan selama pengiriman dengan layanan pengiriman terpercaya.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Image Gallery with improved styling -->
        <div class="mt-16" data-aos="fade-up">
            <div class="max-w-3xl mx-auto text-center mb-12">
                <span class="text-orange-500 font-semibold text-sm uppercase tracking-wider mb-2 inline-block">Koleksi Kami</span>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Koleksi Ikan Kami</h2>
                <div class="w-16 h-1 bg-orange-500 mx-auto mb-6"></div>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Jelajahi berbagai jenis ikan koi dan koki berkualitas pilihan yang kami budidayakan dengan perawatan terbaik
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Koi Kohaku -->
                <div class="group bg-white rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2" data-aos="fade-up" data-aos-delay="0">
                    <div class="relative overflow-hidden h-64">
                        <img src="https://tse2.mm.bing.net/th?id=OIP.4zxKzmne9Suf7sOaY91AYgHaE7&pid=Api&P=0&h=180" alt="Ikan Koi Kohaku" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" onerror="this.src='{{ asset('Images/Default-fish.png') }}'">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end">
                            <div class="p-4 w-full">
                                <span class="px-3 py-1 bg-orange-500 text-white text-xs font-bold rounded-full">Premium</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Koi Kohaku</h3>
                        <p class="text-gray-600 mb-4">Koi Kohaku adalah ikan koi dengan pola berwarna merah dan putih yang menjadi ikon koi klasik Jepang.</p>
                        <div class="flex items-center text-sm text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Ukuran: 25-35 cm</span>
                        </div>
                    </div>
                </div>

                <!-- Koi Showa -->
                <div class="group bg-white rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2" data-aos="fade-up" data-aos-delay="100">
                    <div class="relative overflow-hidden h-64">
                        <img src="https://tse4.mm.bing.net/th?id=OIP.1PyCVQIKWr0_QjfyHygx0QHaEv&pid=Api&P=0&h=180" alt="Ikan Koi Showa" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" onerror="this.src='{{ asset('Images/Default-fish.png') }}'">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end">
                            <div class="p-4 w-full">
                                <span class="px-3 py-1 bg-orange-500 text-white text-xs font-bold rounded-full">Best Seller</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Koi Showa</h3>
                        <p class="text-gray-600 mb-4">Koi Showa memiliki kombinasi warna hitam, merah, dan putih yang menawan dengan pola yang kompleks.</p>
                        <div class="flex items-center text-sm text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Ukuran: 20-30 cm</span>
                        </div>
                    </div>
                </div>

                <!-- Koki Oranda -->
                <div class="group bg-white rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2" data-aos="fade-up" data-aos-delay="200">
                    <div class="relative overflow-hidden h-64">
                        <img src="https://tse1.mm.bing.net/th?id=OIP.akes9O3fs58PmL0wYvgXLQHaE8&pid=Api&P=0&h=180" alt="Ikan Koki Oranda" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" onerror="this.src='{{ asset('Images/Default-fish.png') }}'">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end">
                            <div class="p-4 w-full">
                                <span class="px-3 py-1 bg-orange-500 text-white text-xs font-bold rounded-full">Populer</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Koki Oranda</h3>
                        <p class="text-gray-600 mb-4">Ikan koki Oranda memiliki ciri khas berupa tonjolan di kepala yang menyerupai mahkota.</p>
                        <div class="flex items-center text-sm text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Ukuran: 15-20 cm</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-10 text-center">
                <a href="{{ url('/produk') }}" class="inline-flex items-center text-orange-600 hover:text-orange-700 font-medium group">
                    Lihat Semua Koleksi
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1 transform transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
            </div>
        </div>

        <!-- CTA Section with better styling -->
        <div class="mt-16 bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl p-12 text-center overflow-hidden relative" data-aos="fade-up">
            <div class="absolute inset-0 opacity-10">
                <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="warning" x="0" y="0" width="80" height="80" patternUnits="userSpaceOnUse">
                            <path d="M40,5 L75,65 H5 L40,5 Z M40,25 V45 M40,50 V55" stroke="currentColor" fill="none" stroke-width="3"/>
                        </pattern>
                    </defs>
                    <rect x="0" y="0" width="100%" height="100%" fill="url(#warning)"/>
                </svg>
            </div>
            <div class="relative z-10">
                <h2 class="text-3xl font-bold text-white mb-4">Ada Keluhan atau Masalah?</h2>
                <p class="text-white text-opacity-90 mb-8 max-w-2xl mx-auto">Jika Anda memiliki keluhan baik dengan pelayanan ataupun sistem kami, silakan ajukan keluhan Anda. Kami siap mendengar dan memberikan solusi terbaik.</p>
                <div class="flex justify-center">
                    <a href="{{ route('keluhan.create') }}" class="bg-white hover:bg-orange-50 text-orange-600 font-semibold py-3 px-8 rounded-md transition duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-1 inline-flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        Ajukan Keluhan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Ensure AOS is initialized properly for this specific page
    document.addEventListener('DOMContentLoaded', function() {
        // Reinitialize AOS for this page
        setTimeout(function() {
            if (typeof AOS !== 'undefined') {
                AOS.refresh();
            }
        }, 100);
    });
</script>
@endpush
@endsection
