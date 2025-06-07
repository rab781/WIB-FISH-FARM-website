@extends('layouts.app')

@section('title', 'Ajukan Refund - Pesanan #' . $pesanan->id_pesanan)

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-1">
                                <i class="fas fa-money-bill-wave me-2 text-success"></i>
                                Ajukan Refund
                            </h4>
                            <p class="text-muted mb-0">Pesanan #{{ $pesanan->id_pesanan }}</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('pesanan.show', $pesanan->id_pesanan) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('refund.store', $pesanan->id_pesanan) }}" method="POST" enctype="multipart/form-data" id="refundForm">
        @csrf
        <input type="hidden" name="id_pesanan" value="{{ $pesanan->id_pesanan }}">
        
        <div class="row">
            <!-- Informasi Pesanan -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-light py-3">
                        <h5 class="mb-0">Informasi Pesanan</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nomor Pesanan</label>
                            <p class="mb-1">#{{ $pesanan->id_pesanan }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggal Pesanan</label>
                            <p class="mb-1">{{ $pesanan->created_at->format('d M Y H:i') }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Status Pesanan</label>
                            <p class="mb-1">{!! $pesanan->status_badge !!}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Total Pesanan</label>
                            <p class="mb-1">{{ $pesanan->formatted_total_harga }}</p>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-bold">Produk</label>
                            @foreach($pesanan->detailPesanan as $detail)
                                <div class="d-flex align-items-center mb-2">
                                    <img src="{{ asset($detail->produk->gambar_produk[0] ?? 'Images/product-placeholder.jpg') }}" alt="{{ $detail->produk->nama_produk }}" class="img-thumbnail me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                    <div>
                                        <p class="mb-0 fw-medium">{{ $detail->produk->nama_produk }}</p>
                                        <small class="text-muted">{{ $detail->kuantitas }} x Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Form Refund -->
            <div class="col-md-8 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-light py-3">
                        <h5 class="mb-0">Informasi Refund</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <div class="d-flex">
                                <i class="fas fa-info-circle mt-1 me-2"></i>
                                <div>
                                    <h6 class="alert-heading mb-1">Penting!</h6>
                                    <p class="mb-0">Refund hanya dapat diajukan untuk pesanan yang sudah selesai dalam waktu 24 jam setelah pesanan diterima.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label for="jenis_refund" class="form-label">Jenis Refund <span class="text-danger">*</span></label>
                                <select name="jenis_refund" id="jenis_refund" class="form-select @error('jenis_refund') is-invalid @enderror" required>
                                    <option value="" selected disabled>Pilih Jenis Refund</option>
                                    <option value="kerusakan" {{ old('jenis_refund') == 'kerusakan' ? 'selected' : '' }}>Produk Rusak</option>
                                    <option value="keterlambatan" {{ old('jenis_refund') == 'keterlambatan' ? 'selected' : '' }}>Keterlambatan Pengiriman</option>
                                    <option value="tidak_sesuai" {{ old('jenis_refund') == 'tidak_sesuai' ? 'selected' : '' }}>Tidak Sesuai Pesanan</option>
                                    <option value="kematian_ikan" {{ old('jenis_refund') == 'kematian_ikan' ? 'selected' : '' }}>Kematian Ikan</option>
                                    <option value="lainnya" {{ old('jenis_refund') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                @error('jenis_refund')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="jumlah_diminta" class="form-label">Jumlah Refund <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" name="jumlah_diminta" id="jumlah_diminta" class="form-control @error('jumlah_diminta') is-invalid @enderror" value="{{ old('jumlah_diminta', $pesanan->total_harga) }}" min="1000" max="{{ $pesanan->total_harga }}" required>
                                </div>
                                <small class="text-muted">Maksimal: {{ $pesanan->formatted_total_harga }}</small>
                                @error('jumlah_diminta')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="deskripsi_masalah" class="form-label">Deskripsi Masalah <span class="text-danger">*</span></label>
                            <textarea name="deskripsi_masalah" id="deskripsi_masalah" rows="4" class="form-control @error('deskripsi_masalah') is-invalid @enderror" placeholder="Jelaskan secara detail masalah yang Anda alami..." required>{{ old('deskripsi_masalah') }}</textarea>
                            <small class="text-muted">Minimal 10 karakter</small>
                            @error('deskripsi_masalah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label for="metode_refund" class="form-label">Metode Refund <span class="text-danger">*</span></label>
                                <select name="metode_refund" id="metode_refund" class="form-select @error('metode_refund') is-invalid @enderror" required>
                                    <option value="" selected disabled>Pilih Metode Refund</option>
                                    <option value="transfer_bank" {{ old('metode_refund') == 'transfer_bank' ? 'selected' : '' }}>Transfer Bank</option>
                                    <option value="ewallet" {{ old('metode_refund') == 'ewallet' ? 'selected' : '' }}>E-Wallet</option>
                                    <option value="kredit_toko" {{ old('metode_refund') == 'kredit_toko' ? 'selected' : '' }}>Kredit Toko</option>
                                </select>
                                @error('metode_refund')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="detail_refund" class="form-label">Detail Metode Refund <span class="text-danger">*</span></label>
                                <input type="text" name="detail_refund" id="detail_refund" class="form-control @error('detail_refund') is-invalid @enderror" value="{{ old('detail_refund') }}" placeholder="Contoh: BCA - 1234567890 - John Doe" required>
                                <small class="text-muted" id="detail_refund_help">Masukkan detail untuk metode refund yang dipilih</small>
                                @error('detail_refund')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="bukti_pendukung" class="form-label">Bukti Pendukung <span class="text-muted">(Opsional)</span></label>
                            <input type="file" name="bukti_pendukung[]" id="bukti_pendukung" class="form-control @error('bukti_pendukung.*') is-invalid @enderror" multiple accept="image/jpeg,image/png,image/jpg">
                            <small class="text-muted">Max 5 files, masing-masing max 2MB (JPG, PNG)</small>
                            @error('bukti_pendukung.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="preview-images row mb-3" id="imagePreviewContainer"></div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-1"></i> Ajukan Refund
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle file preview
        const input = document.getElementById('bukti_pendukung');
        const previewContainer = document.getElementById('imagePreviewContainer');
        
        input.addEventListener('change', function() {
            previewContainer.innerHTML = '';
            
            if (this.files.length > 5) {
                alert('Maksimal 5 file yang dapat diunggah');
                this.value = '';
                return;
            }
            
            for (let i = 0; i < this.files.length; i++) {
                const file = this.files[i];
                
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file melebihi batas 2MB');
                    this.value = '';
                    previewContainer.innerHTML = '';
                    return;
                }
                
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const col = document.createElement('div');
                    col.className = 'col-md-3 col-6 mb-3';
                    
                    const card = document.createElement('div');
                    card.className = 'card h-100';
                    
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'card-img-top';
                    img.style = 'height: 120px; object-fit: cover;';
                    
                    const cardBody = document.createElement('div');
                    cardBody.className = 'card-body p-2';
                    
                    const fileName = document.createElement('p');
                    fileName.className = 'card-text small text-truncate mb-0';
                    fileName.textContent = file.name;
                    
                    cardBody.appendChild(fileName);
                    card.appendChild(img);
                    card.appendChild(cardBody);
                    col.appendChild(card);
                    
                    previewContainer.appendChild(col);
                };
                
                reader.readAsDataURL(file);
            }
        });
        
        // Update detail refund help text based on selected method
        const metodeRefund = document.getElementById('metode_refund');
        const detailRefundHelp = document.getElementById('detail_refund_help');
        
        metodeRefund.addEventListener('change', function() {
            switch(this.value) {
                case 'transfer_bank':
                    detailRefundHelp.textContent = 'Format: Nama Bank - No. Rekening - Nama Pemilik Rekening';
                    document.getElementById('detail_refund').placeholder = 'Contoh: BCA - 1234567890 - John Doe';
                    break;
                case 'ewallet':
                    detailRefundHelp.textContent = 'Format: Nama Provider - Nomor Telepon';
                    document.getElementById('detail_refund').placeholder = 'Contoh: GoPay - 08123456789';
                    break;
                case 'kredit_toko':
                    detailRefundHelp.textContent = 'Refund akan dikonversi ke kredit toko yang dapat dipakai untuk pembelian selanjutnya';
                    document.getElementById('detail_refund').placeholder = 'Ketik SETUJU';
                    break;
                default:
                    detailRefundHelp.textContent = 'Masukkan detail untuk metode refund yang dipilih';
                    document.getElementById('detail_refund').placeholder = '';
            }
        });
        
        // Form validation
        const form = document.getElementById('refundForm');
        form.addEventListener('submit', function(event) {
            const jenisRefund = document.getElementById('jenis_refund').value;
            const deskripsiMasalah = document.getElementById('deskripsi_masalah').value;
            const metodeRefund = document.getElementById('metode_refund').value;
            const detailRefund = document.getElementById('detail_refund').value;
            
            let hasError = false;
            
            if (!jenisRefund) {
                document.getElementById('jenis_refund').classList.add('is-invalid');
                hasError = true;
            }
            
            if (deskripsiMasalah.length < 10) {
                document.getElementById('deskripsi_masalah').classList.add('is-invalid');
                hasError = true;
            }
            
            if (!metodeRefund) {
                document.getElementById('metode_refund').classList.add('is-invalid');
                hasError = true;
            }
            
            if (!detailRefund) {
                document.getElementById('detail_refund').classList.add('is-invalid');
                hasError = true;
            }
            
            if (hasError) {
                event.preventDefault();
                alert('Mohon lengkapi semua field yang wajib diisi dengan benar!');
            }
        });
    });
</script>
@endpush
