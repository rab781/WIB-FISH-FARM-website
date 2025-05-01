@extends('layouts.customer')

@section('content')
<!-- Hero Section -->
<section class="relative py-20 bg-gradient-to-br from-orange-50 to-orange-100">
    <div class="container mx-auto px-6">
        <div class="flex flex-col md:flex-row items-center">
            <div class="md:w-1/2 mb-8 md:mb-0" data-aos="fade-right">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Tentang WIB Fish Farm</h1>
                <p class="text-lg text-gray-700 mb-6">Membudidayakan ikan hias berkualitas dengan dedikasi dan passion sejak 2008.</p>
                <p class="text-gray-600">
                    WIB Fish Farm adalah pionir dalam budidaya ikan hias Koi dan Koki berkualitas di Jember, Jawa Timur. 
                    Dengan komitmen untuk menyediakan ikan hias terbaik, kami menggabungkan tradisi dan inovasi modern 
                    dalam setiap aspek budidaya kami.
                </p>
            </div>
            <div class="md:w-1/2 flex justify-center" data-aos="fade-left">
                <div class="relative">
                    <div class="absolute inset-0 bg-orange-400 rounded-lg transform rotate-3"></div>
                    <img src="{{ asset('Images/Logo_WIB_FISH_FARM.png') }}" alt="WIB Fish Farm" class="relative rounded-lg w-80 h-80 object-contain bg-white shadow-lg">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Visi & Misi Section -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-6">
        <div class="max-w-3xl mx-auto text-center mb-12" data-aos="fade-up">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Visi & Misi Kami</h2>
            <div class="w-16 h-1 bg-orange-500 mx-auto mb-6"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-gray-50 p-8 rounded-lg shadow-md transform transition duration-300 hover:-translate-y-1 hover:shadow-lg" data-aos="fade-right">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Visi</h3>
                </div>
                <p class="text-gray-600">
                    Menjadi pusat budidaya ikan hias terkemuka di Indonesia yang dikenal akan kualitas, keberlanjutan, dan inovasi.
                </p>
            </div>

            <div class="bg-gray-50 p-8 rounded-lg shadow-md transform transition duration-300 hover:-translate-y-1 hover:shadow-lg" data-aos="fade-left">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Misi</h3>
                </div>
                <ul class="text-gray-600 space-y-2">
                    <li class="flex items-start">
                        <svg class="w-4 h-4 text-orange-500 mt-1 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Mengembangkan teknik budidaya yang inovatif untuk menghasilkan ikan hias berkualitas tinggi.</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-4 h-4 text-orange-500 mt-1 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Mempraktikkan budidaya yang berkelanjutan dan ramah lingkungan.</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-4 h-4 text-orange-500 mt-1 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Memberikan edukasi tentang pemeliharaan ikan hias kepada konsumen.</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-4 h-4 text-orange-500 mt-1 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Menyediakan pelayanan terbaik untuk kepuasan pelanggan.</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-6">
        <div class="max-w-3xl mx-auto text-center mb-12" data-aos="fade-up">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Tim Kami</h2>
            <div class="w-16 h-1 bg-orange-500 mx-auto mb-6"></div>
            <p class="text-gray-600">
                Kenali orang-orang berbakat di balik kesuksesan WIB Fish Farm yang membawa passion 
                dan dedikasi dalam budidaya ikan hias berkualitas.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($teamMembers as $member)
            <div class="bg-white rounded-lg shadow-md overflow-hidden transform transition duration-300 hover:-translate-y-2 hover:shadow-xl" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                <div class="h-56 bg-gray-200">
                    <img src="{{ asset($member['image']) }}" alt="{{ $member['name'] }}" class="w-full h-full object-cover" onerror="this.src='{{ asset('Images/Default-fish.png') }}'">
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $member['name'] }}</h3>
                    <p class="text-orange-600 font-medium mb-3">{{ $member['role'] }}</p>
                    <p class="text-gray-600">{{ $member['bio'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Milestones Section -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-6">
        <div class="max-w-3xl mx-auto text-center mb-12" data-aos="fade-up">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Perjalanan Kami</h2>
            <div class="w-16 h-1 bg-orange-500 mx-auto mb-6"></div>
            <p class="text-gray-600">
                Lima belas tahun perjalanan WIB Fish Farm dalam mengembangkan budidaya ikan hias berkualitas di Indonesia.
            </p>
        </div>

        <div class="relative">
            <!-- Timeline Line -->
            <div class="absolute left-1/2 transform -translate-x-1/2 h-full w-1 bg-orange-200"></div>

            <!-- Timeline Items -->
            <div class="relative z-10">
                @foreach($milestones as $index => $milestone)
                <div class="flex flex-col md:flex-row items-center mb-12" data-aos="{{ $index % 2 == 0 ? 'fade-right' : 'fade-left' }}">
                    <div class="md:w-1/2 {{ $index % 2 == 0 ? 'md:pr-12 md:text-right' : 'md:pl-12 md:order-last' }}">
                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $milestone['title'] }}</h3>
                            <p class="text-gray-600">{{ $milestone['description'] }}</p>
                        </div>
                    </div>
                    <div class="md:w-0 flex items-center justify-center my-4 md:my-0">
                        <div class="w-12 h-12 bg-orange-500 rounded-full flex items-center justify-center text-white font-bold shadow-md">
                            {{ $milestone['year'] }}
                        </div>
                    </div>
                    <div class="md:w-1/2 {{ $index % 2 == 0 ? 'md:pl-12 md:order-last' : 'md:pr-12 md:text-right' }}">
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="py-16 bg-gray-900 text-white">
    <div class="container mx-auto px-6">
        <div class="max-w-3xl mx-auto text-center mb-12" data-aos="fade-up">
            <h2 class="text-3xl font-bold mb-4">Apa Kata Pelanggan Kami</h2>
            <div class="w-16 h-1 bg-orange-500 mx-auto mb-6"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-gray-800 p-6 rounded-lg transform transition duration-300 hover:-translate-y-1 hover:shadow-xl" data-aos="fade-up" data-aos-delay="0">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-orange-500 rounded-full flex items-center justify-center text-white mr-4">
                        <span class="text-lg font-bold">D</span>
                    </div>
                    <div>
                        <h3 class="font-bold">Dewi Susanti</h3>
                        <p class="text-gray-400 text-sm">Jakarta</p>
                    </div>
                </div>
                <p class="text-gray-300 italic">"Saya sangat puas dengan koi yang saya beli. Ikannya sehat dan warna-warninya sangat cantik. Pengiriman cepat dan ikan tiba dalam keadaan prima. Terima kasih WIB Fish Farm!"</p>
                <div class="mt-4 flex text-orange-400">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                </div>
            </div>

            <div class="bg-gray-800 p-6 rounded-lg transform transition duration-300 hover:-translate-y-1 hover:shadow-xl" data-aos="fade-up" data-aos-delay="100">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-orange-500 rounded-full flex items-center justify-center text-white mr-4">
                        <span class="text-lg font-bold">B</span>
                    </div>
                    <div>
                        <h3 class="font-bold">Budi Santoso</h3>
                        <p class="text-gray-400 text-sm">Surabaya</p>
                    </div>
                </div>
                <p class="text-gray-300 italic">"Ikan koki dari WIB Fish Farm memiliki bentuk dan kualitas yang sangat bagus. Sebagai kolektor, saya menghargai ketelitian mereka dalam memilih dan memelihara ikan. Layanan pelanggan juga sangat responsif."</p>
                <div class="mt-4 flex text-orange-400">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                </div>
            </div>

            <div class="bg-gray-800 p-6 rounded-lg transform transition duration-300 hover:-translate-y-1 hover:shadow-xl" data-aos="fade-up" data-aos-delay="200">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-orange-500 rounded-full flex items-center justify-center text-white mr-4">
                        <span class="text-lg font-bold">A</span>
                    </div>
                    <div>
                        <h3 class="font-bold">Arief Wicaksono</h3>
                        <p class="text-gray-400 text-sm">Bandung</p>
                    </div>
                </div>
                <p class="text-gray-300 italic">"WIB Fish Farm tidak hanya menjual ikan, tapi juga memberikan edukasi tentang cara merawat ikan dengan baik. Hal ini sangat membantu bagi pemula seperti saya. Ikan-ikan yang saya beli tumbuh dengan baik!"</p>
                <div class="mt-4 flex text-orange-400">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-gradient-to-br from-orange-500 to-orange-600 text-white">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-3xl font-bold mb-6" data-aos="fade-up">Bersiap Memulai Hobi Ikan Hias?</h2>
            <p class="text-lg mb-8" data-aos="fade-up" data-aos-delay="100">
                Jelajahi koleksi ikan koi dan koki berkualitas kami dan jadilah bagian dari komunitas pecinta ikan hias.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4" data-aos="fade-up" data-aos-delay="200">
                <a href="{{ route('produk') }}" class="bg-white text-orange-600 hover:bg-gray-100 py-3 px-8 rounded-md font-medium transition transform hover:-translate-y-1">
                    Lihat Produk Kami
                </a>
                <a href="#" class="border-2 border-white text-white hover:bg-white/20 py-3 px-8 rounded-md font-medium transition transform hover:-translate-y-1">
                    Hubungi Kami
                </a>
            </div>
        </div>
    </div>
</section>
@endsection