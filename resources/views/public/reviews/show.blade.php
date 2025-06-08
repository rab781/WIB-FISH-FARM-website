@extends('layouts.app')

@section('title', 'Review Produk - ' . ($review->produk->nama ?? 'Produk'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <div class="flex items-center space-x-2 text-sm text-gray-500">
            <a href="{{ route('public.reviews.index') }}" class="hover:text-orange-600 transition-colors duration-200">Review Produk</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-gray-800">{{ $review->produk->nama ?? 'Detail Review' }}</span>
        </div>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Review Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Review Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <!-- Review Header -->
                <div class="flex items-start justify-between mb-6">
                    <div class="flex items-start space-x-4">
                        <!-- User Avatar -->
                        <div class="flex-shrink-0">
                            <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center">
                                <span class="text-orange-600 font-bold text-xl">
                                    {{ substr($review->user->name ?? 'A', 0, 1) }}
                                </span>
                            </div>
                        </div>

                        <!-- User Info and Rating -->
                        <div>
                            <div class="flex items-center mb-2">
                                <h2 class="text-xl font-bold text-gray-800 mr-3">{{ $review->user->name ?? 'Anonymous' }}</h2>
                                @if($review->is_verified_buyer)
                                    <span class="px-3 py-1 bg-green-100 text-green-800 text-sm font-medium rounded-full">
                                        ✅ Pembeli Terverifikasi
                                    </span>
                                @endif
                            </div>
                            <div class="flex items-center mb-3">
                                <div class="flex items-center mr-4">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-6 h-6 {{ $i <= $review->rating ? 'text-orange-400' : 'text-gray-300' }}"
                                             fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                    <span class="ml-3 text-lg font-semibold text-gray-700">({{ $review->rating }}/5)</span>
                                </div>
                            </div>
                            <p class="text-sm text-gray-500">
                                Ditulis pada {{ $review->created_at->format('d M Y') }} •
                                Diperbarui {{ $review->updated_at->format('d M Y') }}
                            </p>
                        </div>
                    </div>

                    <!-- Review Actions -->
                    <div class="flex items-center space-x-3">
                        <button onclick="toggleHelpful({{ $review->id }})"
                                class="flex items-center px-4 py-2 text-sm border rounded-lg hover:bg-gray-50 transition-colors duration-200
                                       {{ $review->user_marked_helpful ? 'border-orange-300 text-orange-600 bg-orange-50' : 'border-gray-300 text-gray-600' }}">
                            <svg class="w-5 h-5 mr-2" fill="{{ $review->user_marked_helpful ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L9 7v13m-3-7H4a1 1 0 01-1-1V9a1 1 0 011-1h2m5-5v2.5"/>
                            </svg>
                            <span id="helpful-count">{{ $review->helpful_count ?? 0 }}</span>
                            <span class="ml-1">Membantu</span>
                        </button>

                        <button onclick="shareReview()"
                                class="flex items-center px-4 py-2 text-sm border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"/>
                            </svg>
                            Bagikan
                        </button>
                    </div>
                </div>

                <!-- Review Content -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Review</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-700 leading-relaxed text-base">{{ $review->komentar }}</p>
                    </div>
                </div>

                <!-- Review Photos -->
                @if($review->photos && count($review->photos) > 0)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Foto Review ({{ count($review->photos) }})</h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($review->photos as $index => $photo)
                                <div class="relative group cursor-pointer" onclick="openPhotoModal({{ $index }})">
                                    <img src="{{ asset('storage/' . $photo) }}"
                                         alt="Review Photo {{ $index + 1 }}"
                                         class="w-full h-32 object-cover rounded-lg border border-gray-200 group-hover:opacity-75 transition-opacity duration-200">
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 rounded-lg transition-all duration-200 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                                        </svg>
                                    </div>
                                    <div class="absolute bottom-2 right-2 bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded">
                                        {{ $index + 1 }}/{{ count($review->photos) }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Admin Reply -->
                @if($review->admin_reply)
                    <div class="border-t pt-6">
                        <div class="bg-orange-50 border border-orange-200 rounded-lg p-6">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-orange-500 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4 flex-1">
                                    <h4 class="text-base font-semibold text-orange-800 mb-2">Balasan dari Admin</h4>
                                    <p class="text-orange-700 leading-relaxed">{{ $review->admin_reply }}</p>
                                    @if($review->admin_reply_at)
                                        <p class="text-sm text-orange-600 mt-3">
                                            Dibalas pada {{ \Carbon\Carbon::parse($review->admin_reply_at)->format('d M Y, H:i') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Review Statistics -->
                <div class="border-t pt-6 mt-6">
                    <div class="flex items-center justify-between text-sm text-gray-500">
                        <div class="flex items-center space-x-6">
                            <span>👁️ {{ $review->views_count ?? 0 }} kali dilihat</span>
                            <span>👍 {{ $review->helpful_count ?? 0 }} orang terbantu</span>
                            @if($review->report_count && $review->report_count > 0)
                                <span>⚠️ {{ $review->report_count }} laporan</span>
                            @endif
                        </div>
                        <div>
                            <button onclick="reportReview()"
                                    class="text-gray-400 hover:text-red-500 transition-colors duration-200">
                                🚨 Laporkan Review
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Similar Reviews -->
            @if($similarReviews && count($similarReviews) > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Review Lainnya untuk Produk Ini</h3>
                    <div class="space-y-4">
                        @foreach($similarReviews as $similarReview)
                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors duration-200">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center mr-3">
                                            <span class="text-orange-600 font-medium text-sm">
                                                {{ substr($similarReview->user->name ?? 'A', 0, 1) }}
                                            </span>
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-gray-800">{{ $similarReview->user->name ?? 'Anonymous' }}</h4>
                                            <div class="flex items-center">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-3 h-3 {{ $i <= $similarReview->rating ? 'text-orange-400' : 'text-gray-300' }}"
                                                         fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                @endfor
                                                <span class="ml-2 text-xs text-gray-500">{{ $similarReview->created_at->format('d M Y') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="{{ route('public.reviews.show', $similarReview->id) }}"
                                       class="text-orange-600 hover:text-orange-700 text-sm font-medium">
                                        Lihat Detail →
                                    </a>
                                </div>
                                <p class="text-sm text-gray-600 line-clamp-2">{{ Str::limit($similarReview->komentar, 120) }}</p>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4 text-center">
                        <a href="{{ route('public.reviews.index', ['produk' => $review->produk->id]) }}"
                           class="text-orange-600 hover:text-orange-700 font-medium">
                            Lihat Semua Review Produk Ini →
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Product Information -->
            @if($review->produk)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="font-semibold text-gray-800 mb-4">Produk yang Direview</h3>
                    <div class="space-y-4">
                        <img src="{{ asset('storage/' . $review->produk->gambar) }}"
                             alt="{{ $review->produk->nama }}"
                             class="w-full h-48 object-cover rounded-lg border border-gray-200">
                        <div>
                            <h4 class="font-semibold text-gray-800 mb-2">{{ $review->produk->nama }}</h4>
                            <p class="text-sm text-gray-600 mb-3">{{ $review->produk->kategori ?? 'Kategori tidak tersedia' }}</p>
                            <p class="text-xl font-bold text-orange-600 mb-4">
                                Rp {{ number_format($review->produk->harga, 0, ',', '.') }}
                            </p>

                            <!-- Product Rating Summary -->
                            @if($productRatingSummary)
                                <div class="bg-gray-50 rounded-lg p-3 mb-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-medium text-gray-700">Rating Produk</span>
                                        <span class="text-sm font-bold text-orange-600">
                                            {{ number_format($productRatingSummary['average'], 1) }}/5
                                        </span>
                                    </div>
                                    <div class="flex items-center mb-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 {{ $i <= $productRatingSummary['average'] ? 'text-orange-400' : 'text-gray-300' }}"
                                                 fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                    <p class="text-xs text-gray-500">{{ $productRatingSummary['total'] }} review</p>
                                </div>
                            @endif

                            <div class="space-y-2">
                                <a href="{{ route('public.products.show', $review->produk->id) }}"
                                   class="w-full bg-orange-500 hover:bg-orange-600 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 text-center block">
                                    Lihat Detail Produk
                                </a>
                                <a href="{{ route('public.reviews.index', ['produk' => $review->produk->id]) }}"
                                   class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-lg transition-colors duration-200 text-center block">
                                    Lihat Semua Review
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Reviewer Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Tentang Reviewer</h3>
                <div class="space-y-3">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mr-3">
                            <span class="text-orange-600 font-bold text-lg">
                                {{ substr($review->user->name ?? 'A', 0, 1) }}
                            </span>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-800">{{ $review->user->name ?? 'Anonymous' }}</h4>
                            @if($review->is_verified_buyer)
                                <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">Pembeli Terverifikasi</span>
                            @endif
                        </div>
                    </div>

                    @if($reviewerStats)
                        <div class="bg-gray-50 rounded-lg p-3">
                            <div class="grid grid-cols-2 gap-3 text-center">
                                <div>
                                    <p class="text-lg font-bold text-gray-800">{{ $reviewerStats['total_reviews'] }}</p>
                                    <p class="text-xs text-gray-600">Total Review</p>
                                </div>
                                <div>
                                    <p class="text-lg font-bold text-gray-800">{{ number_format($reviewerStats['average_rating'], 1) }}</p>
                                    <p class="text-xs text-gray-600">Rata-rata Rating</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="text-sm text-gray-600">
                        <p>Bergabung {{ $review->user->created_at ? $review->user->created_at->format('M Y') : 'Tidak diketahui' }}</p>
                        <p>Review pertama {{ $review->created_at->format('d M Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Review Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Aksi</h3>
                <div class="space-y-3">
                    <button onclick="toggleHelpful({{ $review->id }})"
                            class="w-full flex items-center justify-center px-4 py-2 border rounded-lg hover:bg-gray-50 transition-colors duration-200
                                   {{ $review->user_marked_helpful ? 'border-orange-300 text-orange-600 bg-orange-50' : 'border-gray-300 text-gray-600' }}">
                        <svg class="w-4 h-4 mr-2" fill="{{ $review->user_marked_helpful ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L9 7v13m-3-7H4a1 1 0 01-1-1V9a1 1 0 011-1h2m5-5v2.5"/>
                        </svg>
                        {{ $review->user_marked_helpful ? 'Sudah Membantu' : 'Tandai Membantu' }}
                    </button>

                    <button onclick="shareReview()"
                            class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"/>
                        </svg>
                        Bagikan Review
                    </button>

                    <button onclick="reportReview()"
                            class="w-full flex items-center justify-center px-4 py-2 border border-red-300 text-red-600 rounded-lg hover:bg-red-50 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 18.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        Laporkan Review
                    </button>
                </div>
            </div>

            <!-- Help Section -->
            <div class="bg-orange-50 border border-orange-200 rounded-lg p-6">
                <h3 class="font-semibold text-orange-800 mb-3">💡 Tentang Review</h3>
                <ul class="text-sm text-orange-700 space-y-2">
                    <li>• Review ini ditulis berdasarkan pengalaman nyata pembeli</li>
                    <li>• Semua review telah dimoderasi sebelum dipublikasikan</li>
                    <li>• Tandai review sebagai "membantu" jika berguna untuk Anda</li>
                    <li>• Laporkan review yang tidak sesuai atau spam</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Photo Modal -->
<div id="photoModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center">
    <div class="relative max-w-4xl max-h-full mx-4">
        <button onclick="closePhotoModal()"
                class="absolute top-4 right-4 text-white hover:text-gray-300 z-10">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <div class="bg-white rounded-lg overflow-hidden">
            <img id="modalImage" src="" alt="Review Photo" class="w-full h-auto max-h-[80vh] object-contain">
            <div class="p-4 bg-gray-50">
                <div class="flex justify-between items-center">
                    <span id="photoCounter" class="text-sm text-gray-600"></span>
                    <div class="flex space-x-2">
                        <button onclick="previousPhoto()"
                                id="prevBtn"
                                class="px-3 py-1 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded transition-colors duration-200">
                            ← Sebelumnya
                        </button>
                        <button onclick="nextPhoto()"
                                id="nextBtn"
                                class="px-3 py-1 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded transition-colors duration-200">
                            Selanjutnya →
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Report Modal -->
<div id="reportModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Laporkan Review</h3>
                <button onclick="closeReportModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form id="reportForm">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Laporan</label>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="radio" name="reason" value="spam" class="mr-2">
                                <span class="text-sm">Spam atau konten berulang</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="reason" value="inappropriate" class="mr-2">
                                <span class="text-sm">Konten tidak pantas</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="reason" value="fake" class="mr-2">
                                <span class="text-sm">Review palsu atau menyesatkan</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="reason" value="offensive" class="mr-2">
                                <span class="text-sm">Bahasa yang menyinggung</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="reason" value="other" class="mr-2">
                                <span class="text-sm">Lainnya</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label for="reportComment" class="block text-sm font-medium text-gray-700 mb-2">
                            Komentar Tambahan (Opsional)
                        </label>
                        <textarea id="reportComment"
                                  rows="3"
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                  placeholder="Jelaskan alasan laporan Anda..."></textarea>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button"
                            onclick="closeReportModal()"
                            class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors duration-200">
                        Kirim Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let currentPhotoIndex = 0;
const photos = @json($review->photos ?? []);

// Photo modal functionality
function openPhotoModal(index) {
    currentPhotoIndex = index;
    updateModalPhoto();
    document.getElementById('photoModal').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closePhotoModal() {
    document.getElementById('photoModal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

function updateModalPhoto() {
    if (photos.length > 0) {
        const modalImage = document.getElementById('modalImage');
        const photoCounter = document.getElementById('photoCounter');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');

        modalImage.src = `/storage/${photos[currentPhotoIndex]}`;
        photoCounter.textContent = `${currentPhotoIndex + 1} dari ${photos.length}`;

        // Toggle navigation buttons
        prevBtn.style.display = currentPhotoIndex > 0 ? 'block' : 'none';
        nextBtn.style.display = currentPhotoIndex < photos.length - 1 ? 'block' : 'none';
    }
}

function previousPhoto() {
    if (currentPhotoIndex > 0) {
        currentPhotoIndex--;
        updateModalPhoto();
    }
}

function nextPhoto() {
    if (currentPhotoIndex < photos.length - 1) {
        currentPhotoIndex++;
        updateModalPhoto();
    }
}

// Keyboard navigation
document.addEventListener('keydown', function(e) {
    if (!document.getElementById('photoModal').classList.contains('hidden')) {
        if (e.key === 'Escape') {
            closePhotoModal();
        } else if (e.key === 'ArrowLeft') {
            previousPhoto();
        } else if (e.key === 'ArrowRight') {
            nextPhoto();
        }
    }
});

// Helpful functionality
function toggleHelpful(reviewId) {
    fetch(`/public/reviews/${reviewId}/helpful`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const buttons = document.querySelectorAll(`button[onclick="toggleHelpful(${reviewId})"]`);
            const helpfulCount = document.getElementById('helpful-count');

            buttons.forEach(button => {
                if (data.helpful) {
                    button.classList.add('border-orange-300', 'text-orange-600', 'bg-orange-50');
                    button.classList.remove('border-gray-300', 'text-gray-600');
                    button.querySelector('svg').setAttribute('fill', 'currentColor');

                    // Update button text if it contains text
                    const textSpan = button.querySelector('span:last-child');
                    if (textSpan && textSpan.textContent.includes('Tandai')) {
                        textSpan.textContent = 'Sudah Membantu';
                    }
                } else {
                    button.classList.remove('border-orange-300', 'text-orange-600', 'bg-orange-50');
                    button.classList.add('border-gray-300', 'text-gray-600');
                    button.querySelector('svg').setAttribute('fill', 'none');

                    // Update button text if it contains text
                    const textSpan = button.querySelector('span:last-child');
                    if (textSpan && textSpan.textContent.includes('Sudah')) {
                        textSpan.textContent = 'Tandai Membantu';
                    }
                }
            });

            if (helpfulCount) {
                helpfulCount.textContent = data.helpful_count;
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            title: 'Error',
            text: 'Terjadi kesalahan saat memproses feedback',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    });
}

// Share functionality
function shareReview() {
    const url = window.location.href;
    const title = 'Review Produk - {{ $review->produk->nama ?? "Produk" }}';
    const text = 'Lihat review produk ini: {{ Str::limit($review->komentar, 100) }}';

    if (navigator.share) {
        navigator.share({
            title: title,
            text: text,
            url: url
        });
    } else {
        // Fallback to clipboard
        navigator.clipboard.writeText(url).then(() => {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Link review berhasil disalin ke clipboard!',
                icon: 'success',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        }).catch(() => {
            // Fallback to manual copy
            const textArea = document.createElement('textarea');
            textArea.value = url;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            Swal.fire({
                title: 'Berhasil!',
                text: 'Link review berhasil disalin!',
                icon: 'success',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        });
    }
}

// Report functionality
function reportReview() {
    document.getElementById('reportModal').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeReportModal() {
    document.getElementById('reportModal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
    document.getElementById('reportForm').reset();
}

// Report form submission
document.getElementById('reportForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const reason = document.querySelector('input[name="reason"]:checked');
    if (!reason) {
        Swal.fire({
            title: 'Alasan Laporan Belum Dipilih',
            text: 'Silakan pilih alasan laporan',
            icon: 'warning',
            confirmButtonText: 'OK'
        });
        return;
    }

    const comment = document.getElementById('reportComment').value;

    fetch(`/public/reviews/{{ $review->id }}/report`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            reason: reason.value,
            comment: comment
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Laporan berhasil dikirim. Terima kasih atas feedback Anda.',
                icon: 'success',
                confirmButtonText: 'OK'
            });
            closeReportModal();
        } else {
            Swal.fire({
                title: 'Gagal!',
                text: 'Gagal mengirim laporan: ' + (data.message || 'Terjadi kesalahan'),
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            title: 'Error',
            text: 'Terjadi kesalahan saat mengirim laporan',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    });
});

// Track page view
document.addEventListener('DOMContentLoaded', function() {
    // Track view for analytics
    fetch(`/public/reviews/{{ $review->id }}/view`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    }).catch(error => {
        console.error('View tracking error:', error);
    });
});
</script>
@endpush
