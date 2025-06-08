@extends('layouts.app')

@section('title', 'Kelola Refund')

@push('styles')
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-material-ui/material-ui.css">
<style>
    :root {
        --primary-color: #f97316;
        --primary-dark: #ea580c;
        --primary-light: #fed7aa;
        --secondary-color: #64748b;
        --success-color: #10b981;
        --danger-color: #ef4444;
        --warning-color: #f59e0b;
        --border-color: #e2e8f0;
        --text-primary: #1e293b;
        --text-secondary: #64748b;
        --bg-light: #f8fafc;
        --shadow-light: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        --shadow-medium: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --shadow-large: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    .gradient-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        position: relative;
        overflow: hidden;
        border-radius: 20px;
        margin-bottom: 2rem;
    }

    .gradient-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='12' cy='12' r='3'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
        animation: float 20s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(180deg); }
    }

    .modern-card {
        background: white;
        border-radius: 16px;
        box-shadow: var(--shadow-light);
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .modern-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-color), var(--primary-dark));
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .modern-card:hover {
        box-shadow: var(--shadow-large);
        transform: translateY(-2px);
    }

    .modern-card:hover::before {
        opacity: 1;
    }

    .dashboard-card {
        background: white;
        border-radius: 16px;
        box-shadow: var(--shadow-medium);
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
        padding: 1.5rem;
        position: relative;
        overflow: hidden;
    }

    .dashboard-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--primary-color), var(--primary-dark));
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .dashboard-card:hover {
        box-shadow: var(--shadow-large);
        transform: translateY(-4px);
    }

    .dashboard-card:hover::before {
        opacity: 1;
    }

    .dashboard-card.gradient-card {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
    }

    .dashboard-card.gradient-card::before {
        display: none;
    }

    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        position: relative;
        overflow: hidden;
    }

    .status-badge::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s ease;
    }

    .status-badge:hover::before {
        left: 100%;
    }

    .status-pending {
        background: linear-gradient(135deg, #fbbf24, #f59e0b);
        color: white;
        box-shadow: 0 4px 12px rgba(251, 191, 36, 0.3);
    }

    .status-approved {
        background: linear-gradient(135deg, var(--success-color), #059669);
        color: white;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .status-rejected {
        background: linear-gradient(135deg, var(--danger-color), #dc2626);
        color: white;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .status-processing {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .status-completed {
        background: linear-gradient(135deg, #6b7280, #4b5563);
        color: white;
        box-shadow: 0 4px 12px rgba(107, 114, 128, 0.3);
    }

    .refund-card {
        background: white;
        border-radius: 16px;
        box-shadow: var(--shadow-light);
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
        padding: 1.5rem;
        position: relative;
        overflow: hidden;
    }

    .refund-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-color), var(--primary-dark));
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .refund-card:hover {
        box-shadow: var(--shadow-large);
        transform: translateY(-3px);
    }

    .refund-card:hover::before {
        opacity: 1;
    }

    .evidence-photo {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 12px;
        border: 3px solid var(--border-color);
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .evidence-photo::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, rgba(249, 115, 22, 0.1), transparent);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .evidence-photo:hover {
        border-color: var(--primary-color);
        transform: scale(1.05);
        box-shadow: var(--shadow-medium);
    }

    .evidence-photo:hover::before {
        opacity: 1;
    }

    .form-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid var(--border-color);
        border-radius: 12px;
        font-size: 0.875rem;
        transition: all 0.3s ease;
        background-color: #ffffff;
    }

    .form-input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
        transform: translateY(-1px);
    }

    .form-input:hover {
        border-color: var(--primary-light);
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        font-size: 0.875rem;
        letter-spacing: 0.025em;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-primary::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-large);
        text-decoration: none;
        color: white;
    }

    .btn-primary:hover::before {
        left: 100%;
    }

    .btn-secondary {
        background: linear-gradient(145deg, #f1f5f9, #e2e8f0);
        color: var(--text-primary);
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: 2px solid var(--border-color);
        cursor: pointer;
        font-size: 0.875rem;
        letter-spacing: 0.025em;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-secondary:hover {
        background: linear-gradient(145deg, #e2e8f0, #cbd5e1);
        transform: translateY(-2px);
        box-shadow: var(--shadow-medium);
        text-decoration: none;
        color: var(--text-primary);
    }

    .fade-in {
        animation: fadeIn 0.5s ease-in;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .product-image-container {
        position: relative;
        overflow: hidden;
        border-radius: 12px;
        box-shadow: var(--shadow-medium);
    }

    .product-image-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, rgba(249, 115, 22, 0.1), transparent);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .product-image-container:hover::before {
        opacity: 1;
    }

    .search-container {
        position: relative;
    }

    .search-container .search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-secondary);
        z-index: 10;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        background: white;
        border-radius: 20px;
        box-shadow: var(--shadow-medium);
        border: 1px solid var(--border-color);
    }

    .section-divider {
        height: 2px;
        background: linear-gradient(90deg, var(--primary-color), var(--primary-dark));
        border-radius: 2px;
        margin: 1.5rem 0;
        position: relative;
    }

    .section-divider::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 12px;
        height: 12px;
        background: white;
        border: 3px solid var(--primary-color);
        border-radius: 50%;
    }

    .photo-grid-item {
        position: relative;
        overflow: hidden;
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .photo-grid-item:hover {
        transform: scale(1.02);
        box-shadow: var(--shadow-large);
    }

    .info-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        color: #1e40af;
        border-radius: 12px;
        font-size: 0.875rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .admin-note {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        border: 2px solid #3b82f6;
        border-radius: 16px;
        padding: 1rem;
        position: relative;
        overflow: hidden;
    }

    .admin-note::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #3b82f6, #2563eb);
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="page-header text-center mb-8">
        <h1 class="text-3xl font-bold text-white mb-2">Kelola Refund</h1>
        <p class="text-orange-100">Lihat dan kelola permintaan refund Anda</p>

        <div class="mt-6 mx-auto max-w-md">
            <a href="{{ route('pesanan.index') }}" class="inline-block bg-white text-orange-600 px-4 py-2 rounded-lg hover:bg-orange-50 transition-colors mr-2">
                <i class="fas fa-shopping-bag mr-2"></i>Pesanan Saya
            </a>
            <a href="{{ route('refunds.create') }}" class="inline-block bg-orange-700 text-white px-4 py-2 rounded-lg hover:bg-orange-800 transition-colors">
                <i class="fas fa-plus mr-2"></i>Ajukan Refund
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="dashboard-card bg-gradient-to-r from-orange-500 to-orange-600 text-white">
            <div class="flex items-center">
                <div class="flex-shrink-0 p-3 rounded-full bg-white bg-opacity-20">
                    <i class="fas fa-undo text-2xl"></i>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold">{{ $stats['total'] ?? 0 }}</div>
                    <div class="text-orange-100">Total Refund</div>
                </div>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-clock text-xl"></i>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['pending'] ?? 0 }}</div>
                    <div class="text-gray-600">Menunggu Review</div>
                </div>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['approved'] ?? 0 }}</div>
                    <div class="text-gray-600">Disetujui</div>
                </div>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-money-bill-wave text-xl"></i>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900">Rp {{ number_format($stats['total_amount'] ?? 0, 0, ',', '.') }}</div>
                    <div class="text-gray-600">Total Dana</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="form-card mb-8">
        <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Filter & Pencarian</h3>
        <form method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-64">
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari Refund</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Nomor pesanan atau ID refund..."
                           class="w-full border border-gray-300 rounded-lg pl-10 px-3 py-2 focus:ring-orange-500 focus:border-orange-500 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500 focus:outline-none">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Review</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Diproses</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 transition-colors">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="{{ route('refunds.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                    <i class="fas fa-undo mr-2"></i>Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Refund List -->
    @if($refunds->count() > 0)
    <div class="space-y-6">
        @foreach($refunds as $refund)
        <div class="refund-card">
            <div class="flex flex-wrap justify-between items-start mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">
                        Refund #{{ $refund->id }}
                    </h3>
                    <p class="text-gray-600">Pesanan: {{ $refund->pesanan->nomor_pesanan }}</p>
                </div>
                <div class="text-right">
                    <span class="status-badge status-{{ $refund->status }}">
                        @switch($refund->status)
                            @case('pending') Menunggu Review @break
                            @case('approved') Disetujui @break
                            @case('rejected') Ditolak @break
                            @case('processing') Sedang Diproses @break
                            @case('completed') Selesai @break
                            @default {{ ucfirst($refund->status) }}
                        @endswitch
                    </span>
                    <div class="text-sm text-gray-500 mt-1">{{ $refund->created_at->format('d/m/Y') }}</div>
                </div>
            </div>

            <!-- Product Info -->
            <div class="flex items-center space-x-4 mb-4 p-4 bg-gray-50 rounded-lg">
                @if($refund->pesanan->produk && $refund->pesanan->produk->gambar)
                <img src="{{ asset('storage/' . $refund->pesanan->produk->gambar) }}"
                     alt="{{ $refund->pesanan->produk->nama }}"
                     class="w-16 h-16 object-cover rounded-lg">
                @endif
                <div class="flex-1">
                    <div class="font-medium text-gray-900">{{ $refund->pesanan->produk->nama ?? 'Produk' }}</div>
                    <div class="text-sm text-gray-600">Kuantitas: {{ $refund->pesanan->kuantitas }} ekor</div>
                    <div class="text-sm font-medium text-orange-600">Rp {{ number_format($refund->amount, 0, ',', '.') }}</div>
                </div>
            </div>

            <!-- Refund Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <span class="text-sm font-medium text-gray-700">Alasan:</span>
                    <div class="text-sm text-gray-900">
                        @switch($refund->reason)
                            @case('defective') Produk Rusak/Cacat @break
                            @case('wrong_item') Barang yang Diterima Salah @break
                            @case('not_as_described') Tidak Sesuai Deskripsi @break
                            @case('dead_fish') Ikan Mati saat Diterima @break
                            @case('other') Lainnya @break
                            @default {{ ucfirst($refund->reason) }}
                        @endswitch
                    </div>
                </div>
                @if($refund->refund_method)
                <div>
                    <span class="text-sm font-medium text-gray-700">Metode Refund:</span>
                    <div class="text-sm text-gray-900">
                        @switch($refund->refund_method)
                            @case('bank_transfer') Transfer Bank @break
                            @case('wallet') E-Wallet @break
                            @case('store_credit') Kredit Toko @break
                            @default {{ ucfirst($refund->refund_method) }}
                        @endswitch
                    </div>
                </div>
                @endif
            </div>

            @if($refund->description)
            <div class="mb-4">
                <span class="text-sm font-medium text-gray-700">Deskripsi:</span>
                <div class="text-sm text-gray-900 mt-1">{{ $refund->description }}</div>
            </div>
            @endif

            @if($refund->admin_notes)
            <div class="mb-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
                <span class="text-sm font-medium text-blue-800">Catatan Admin:</span>
                <div class="text-sm text-blue-900 mt-1">{{ $refund->admin_notes }}</div>
            </div>
            @endif

            <!-- Evidence Photos -->
            @if($refund->evidence_photos && count($refund->evidence_photos) > 0)
            <div class="mb-4">
                <span class="text-sm font-medium text-gray-700 mb-2 block">Bukti Foto:</span>
                <div class="flex space-x-2">
                    @foreach(array_slice($refund->evidence_photos, 0, 3) as $photo)
                    <img src="{{ asset('storage/' . $photo) }}"
                         alt="Bukti foto"
                         class="evidence-photo"
                         onclick="openPhotoModal('{{ asset('storage/' . $photo) }}')">
                    @endforeach
                    @if(count($refund->evidence_photos) > 3)
                    <div class="w-20 h-20 bg-gray-100 rounded-lg border border-gray-200 flex items-center justify-center text-gray-500 text-sm hover:bg-gray-200 cursor-pointer transition-colors" onclick="openAllPhotosModal()">
                        <div>
                            <i class="fas fa-images block mb-1"></i>
                            +{{ count($refund->evidence_photos) - 3 }} lagi
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex justify-between items-center">
                <div class="text-sm text-gray-500">
                    Diajukan {{ $refund->created_at->diffForHumans() }}
                </div>
                <div class="space-x-2">
                    <a href="{{ route('refunds.show', $refund) }}"
                       class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 text-sm">
                        <i class="fas fa-eye mr-2"></i>Detail
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($refunds->hasPages())
    <div class="mt-8">
        {{ $refunds->appends(request()->all())->links() }}
    </div>
    @endif

    @else
    <!-- Empty State -->
    <div class="text-center py-12">
        <div class="text-gray-400 text-6xl mb-4">
            <i class="fas fa-undo"></i>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Refund</h3>
        <p class="text-gray-600 mb-6">Anda belum pernah mengajukan permintaan refund.</p>
        <a href="{{ route('pesanan.index') }}"
           class="bg-orange-600 text-white px-6 py-3 rounded-lg hover:bg-orange-700 inline-flex items-center">
            <i class="fas fa-shopping-bag mr-2"></i>
            Lihat Pesanan
        </a>
    </div>
    @endif
</div>

<!-- Photo Modal -->
<div id="photoModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-black opacity-75" onclick="closePhotoModal()"></div>
        </div>
        <div class="inline-block align-middle bg-white rounded-lg overflow-hidden shadow-xl transform transition-all max-w-lg w-full">
            <div class="bg-white">
                <div class="flex justify-between items-center p-4 border-b">
                    <h3 class="text-lg font-medium text-gray-900">Bukti Foto</h3>
                    <button onclick="closePhotoModal()" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="p-4">
                    <img id="modalPhoto" src="" alt="Bukti foto" class="w-full h-auto max-h-96 object-contain rounded-lg">
                </div>
                <div class="bg-gray-50 px-4 py-3 flex justify-end">
                    <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors focus:outline-none" onclick="closePhotoModal()">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- All Photos Modal -->
<div id="allPhotosModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-black opacity-75" onclick="closeAllPhotosModal()"></div>
        </div>
        <div class="inline-block align-middle bg-white rounded-lg overflow-hidden shadow-xl transform transition-all max-w-3xl w-full">
            <div class="bg-white">
                <div class="flex justify-between items-center p-4 border-b">
                    <h3 class="text-lg font-medium text-gray-900">Semua Bukti Foto</h3>
                    <button onclick="closeAllPhotosModal()" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4" id="allPhotosContainer">
                        <!-- Photos will be added here by JS -->
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 flex justify-end">
                    <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors focus:outline-none" onclick="closeAllPhotosModal()">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
<script>
// Current refund being viewed for all photos modal
let currentRefundPhotos = [];

function openPhotoModal(photoUrl) {
    document.getElementById('modalPhoto').src = photoUrl;
    document.getElementById('photoModal').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closePhotoModal() {
    document.getElementById('photoModal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

function openAllPhotosModal(refundId, photos) {
    // Store the photos in the global variable
    if (photos) {
        currentRefundPhotos = photos;
    }

    // Clear the container
    const container = document.getElementById('allPhotosContainer');
    container.innerHTML = '';

    // Populate with all photos
    currentRefundPhotos.forEach(photo => {
        const photoElement = document.createElement('div');
        photoElement.className = 'evidence-photo-container';
        photoElement.innerHTML = `
            <img src="${photo}" alt="Bukti foto" class="evidence-photo w-full h-48 cursor-pointer" onclick="openPhotoModal('${photo}')">
        `;
        container.appendChild(photoElement);
    });

    document.getElementById('allPhotosModal').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeAllPhotosModal() {
    document.getElementById('allPhotosModal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

// Close modals when clicking outside
document.getElementById('photoModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePhotoModal();
    }
});

document.getElementById('allPhotosModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeAllPhotosModal();
    }
});

// Escape key closes modals
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closePhotoModal();
        closeAllPhotosModal();
    }
});
</script>
@endpush
