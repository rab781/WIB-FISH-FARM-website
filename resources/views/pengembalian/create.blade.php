@extends('layouts.app')

@section('title', 'Ajukan Pengembalian - Pesanan #' . $pesanan->id_pesanan)

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
                                <i class="fas fa-undo-alt me-2 text-warning"></i>
                                Ajukan Pengembalian
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

    <form action="{{ route('pengembalian.store', $pesanan->id_pesanan) }}" method="POST" enctype="multipart/form-data" id="returnForm">
        @csrf
        <div class="row">
            <!-- Order Information -->
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-shopping-bag me-2"></i>
                            Informasi Pesanan
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>ID Pesanan:</strong>
                            <span class="ms-2">{{ $pesanan->id_pesanan }}</span>
                        </div>
                        <div class="mb-3">
                            <strong>Total Pesanan:</strong>
                            <span class="ms-2">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                        </div>
                        <div class="mb-3">
                            <strong>Status:</strong>
                            <span class="badge bg-info ms-2">{{ $pesanan->status_pesanan }}</span>
                        </div>
                        <div class="mb-3">
                            <strong>Tanggal Pesanan:</strong>
                            <span class="ms-2">{{ $pesanan->created_at->format('d/m/Y') }}</span>
                        </div>
                        @if($pesanan->tanggal_pengiriman)
                        <div class="mb-3">
                            <strong>Tanggal Pengiriman:</strong>
                            <span class="ms-2">{{ \Carbon\Carbon::parse($pesanan->tanggal_pengiriman)->format('d/m/Y') }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Order Items -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-list me-2"></i>
                            Barang yang Dipesan
                        </h6>
                    </div>
                    <div class="card-body">
                        @foreach($pesanan->detailPesanan as $detail)
                        <div class="d-flex align-items-center mb-3">
                            @if($detail->produk && $detail->produk->foto_produk)
                                <img src="{{ Storage::url($detail->produk->foto_produk) }}"
                                     alt="{{ $detail->produk->nama_produk }}"
                                     class="img-thumbnail me-3"
                                     style="width: 50px; height: 50px; object-fit: cover;">
                            @endif
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $detail->produk->nama_produk ?? 'Produk tidak ditemukan' }}</h6>
                                <small class="text-muted">
                                    {{ $detail->jumlah }}x @ Rp {{ number_format($detail->harga, 0, ',', '.') }}
                                </small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Return Form -->
            <div class="col-md-8">
                <!-- Complaint Details -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Detail Keluhan
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="jenis_keluhan" class="form-label">Jenis Keluhan <span class="text-danger">*</span></label>
                                    <select name="jenis_keluhan" id="jenis_keluhan" class="form-select @error('jenis_keluhan') is-invalid @enderror" required>
                                        <option value="">Pilih Jenis Keluhan</option>
                                        <option value="Barang Rusak" {{ old('jenis_keluhan') === 'Barang Rusak' ? 'selected' : '' }}>Barang Rusak/Cacat</option>
                                        <option value="Barang Tidak Sesuai" {{ old('jenis_keluhan') === 'Barang Tidak Sesuai' ? 'selected' : '' }}>Barang Tidak Sesuai Deskripsi</option>
                                        <option value="Barang Salah" {{ old('jenis_keluhan') === 'Barang Salah' ? 'selected' : '' }}>Barang Salah/Tidak Lengkap</option>
                                        <option value="Masalah Pengiriman" {{ old('jenis_keluhan') === 'Masalah Pengiriman' ? 'selected' : '' }}>Masalah Pengiriman</option>
                                        <option value="Lainnya" {{ old('jenis_keluhan') === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                    @error('jenis_keluhan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="jenis_pengembalian" class="form-label">Jenis Pengembalian <span class="text-danger">*</span></label>
                                    <select name="jenis_pengembalian" id="jenis_pengembalian" class="form-select @error('jenis_pengembalian') is-invalid @enderror" required>
                                        <option value="">Pilih Jenis Pengembalian</option>
                                        <option value="Refund" {{ old('jenis_pengembalian') === 'Refund' ? 'selected' : '' }}>Pengembalian Dana (Refund)</option>
                                        <option value="Tukar Barang" {{ old('jenis_pengembalian') === 'Tukar Barang' ? 'selected' : '' }}>Tukar Barang</option>
                                    </select>
                                    @error('jenis_pengembalian')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi_keluhan" class="form-label">Deskripsi Keluhan <span class="text-danger">*</span></label>
                            <textarea name="deskripsi_keluhan" id="deskripsi_keluhan" rows="4"
                                      class="form-control @error('deskripsi_keluhan') is-invalid @enderror"
                                      placeholder="Jelaskan detail masalah yang Anda alami..." required>{{ old('deskripsi_keluhan') }}</textarea>
                            @error('deskripsi_keluhan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Minimal 20 karakter. Semakin detail, semakin cepat kami dapat membantu Anda.</div>
                        </div>

                        <div class="mb-3">
                            <label for="jumlah_refund" class="form-label">Jumlah Refund (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="jumlah_refund" id="jumlah_refund"
                                   class="form-control @error('jumlah_refund') is-invalid @enderror"
                                   value="{{ old('jumlah_refund', $pesanan->total_harga) }}"
                                   min="1" max="{{ $pesanan->total_harga }}" required>
                            @error('jumlah_refund')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Maksimal: Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</div>
                        </div>

                        <div class="mb-3">
                            <label for="foto_bukti" class="form-label">Foto Bukti</label>
                            <input type="file" name="foto_bukti[]" id="foto_bukti"
                                   class="form-control @error('foto_bukti') is-invalid @enderror"
                                   multiple accept="image/*">
                            @error('foto_bukti')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Upload foto yang menunjukkan masalah. Format: JPG, PNG, JPEG. Maksimal 5 foto, masing-masing maksimal 2MB.
                            </div>
                            <div id="photoPreview" class="mt-2"></div>
                        </div>
                    </div>
                </div>

                <!-- Bank Information (for refund) -->
                <div class="card mb-4" id="bankInfoCard" style="display: none;">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-university me-2"></i>
                            Informasi Rekening untuk Pengembalian Dana
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Pastikan informasi rekening yang Anda masukkan benar. Dana akan dikembalikan ke rekening ini.
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="bank_name" class="form-label">Bank <span class="text-danger">*</span></label>
                                    <select name="bank_name" id="bank_name" class="form-select @error('bank_name') is-invalid @enderror">
                                        <option value="">Pilih Bank</option>
                                        <option value="BCA" {{ old('bank_name') === 'BCA' ? 'selected' : '' }}>Bank Central Asia (BCA)</option>
                                        <option value="BRI" {{ old('bank_name') === 'BRI' ? 'selected' : '' }}>Bank Rakyat Indonesia (BRI)</option>
                                        <option value="BNI" {{ old('bank_name') === 'BNI' ? 'selected' : '' }}>Bank Negara Indonesia (BNI)</option>
                                        <option value="Mandiri" {{ old('bank_name') === 'Mandiri' ? 'selected' : '' }}>Bank Mandiri</option>
                                        <option value="CIMB" {{ old('bank_name') === 'CIMB' ? 'selected' : '' }}>CIMB Niaga</option>
                                        <option value="Danamon" {{ old('bank_name') === 'Danamon' ? 'selected' : '' }}>Bank Danamon</option>
                                        <option value="BTN" {{ old('bank_name') === 'BTN' ? 'selected' : '' }}>Bank Tabungan Negara (BTN)</option>
                                        <option value="Permata" {{ old('bank_name') === 'Permata' ? 'selected' : '' }}>Bank Permata</option>
                                        <option value="Lainnya" {{ old('bank_name') === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                    @error('bank_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="account_number" class="form-label">Nomor Rekening <span class="text-danger">*</span></label>
                                    <input type="text" name="account_number" id="account_number"
                                           class="form-control @error('account_number') is-invalid @enderror"
                                           value="{{ old('account_number') }}"
                                           placeholder="Contoh: 1234567890">
                                    @error('account_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="account_holder_name" class="form-label">Nama Pemegang Rekening <span class="text-danger">*</span></label>
                                    <input type="text" name="account_holder_name" id="account_holder_name"
                                           class="form-control @error('account_holder_name') is-invalid @enderror"
                                           value="{{ old('account_holder_name', auth()->user()->name) }}"
                                           placeholder="Sesuai dengan KTP">
                                    @error('account_holder_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Agreement -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="form-check">
                            <input type="checkbox" name="agreement" id="agreement" class="form-check-input @error('agreement') is-invalid @enderror" required>
                            <label for="agreement" class="form-check-label">
                                Saya menyatakan bahwa informasi yang saya berikan adalah benar dan saya memahami
                                <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">syarat dan ketentuan</a>
                                pengembalian barang.
                            </label>
                            @error('agreement')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Tim kami akan meninjau pengajuan Anda dalam 1-3 hari kerja.
                                </small>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                <i class="fas fa-paper-plane me-2"></i>
                                Ajukan Pengembalian
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Terms Modal -->
<div class="modal fade" id="termsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Syarat dan Ketentuan Pengembalian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h6>1. Ketentuan Umum</h6>
                <ul>
                    <li>Pengembalian hanya dapat dilakukan maksimal 7 hari setelah barang diterima</li>
                    <li>Barang harus dalam kondisi asli dan belum digunakan (kecuali untuk klaim kerusakan)</li>
                    <li>Semua pengajuan pengembalian akan ditinjau oleh tim kami</li>
                </ul>

                <h6>2. Dokumentasi</h6>
                <ul>
                    <li>Foto bukti yang jelas menunjukkan masalah</li>
                    <li>Deskripsi keluhan yang detail dan akurat</li>
                    <li>Informasi rekening yang valid untuk pengembalian dana</li>
                </ul>

                <h6>3. Proses Pengembalian</h6>
                <ul>
                    <li>Review pengajuan: 1-3 hari kerja</li>
                    <li>Pengembalian dana: 7-14 hari kerja setelah disetujui</li>
                    <li>Biaya pengiriman untuk pengembalian barang ditanggung pembeli (jika diperlukan)</li>
                </ul>

                <h6>4. Penolakan</h6>
                <p>Pengajuan dapat ditolak jika:</p>
                <ul>
                    <li>Tidak sesuai dengan ketentuan</li>
                    <li>Bukti tidak memadai</li>
                    <li>Barang sudah melewati batas waktu pengembalian</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const jenisKembali = document.getElementById('jenis_pengembalian');
    const bankCard = document.getElementById('bankInfoCard');
    const bankFields = ['bank_name', 'account_number', 'account_holder_name'];

    // Toggle bank information visibility
    function toggleBankInfo() {
        if (jenisKembali.value === 'Refund') {
            bankCard.style.display = 'block';
            bankFields.forEach(field => {
                document.getElementById(field).required = true;
            });
        } else {
            bankCard.style.display = 'none';
            bankFields.forEach(field => {
                document.getElementById(field).required = false;
            });
        }
    }

    jenisKembali.addEventListener('change', toggleBankInfo);

    // Initial check
    toggleBankInfo();

    // Photo preview
    const photoInput = document.getElementById('foto_bukti');
    const photoPreview = document.getElementById('photoPreview');

    photoInput.addEventListener('change', function() {
        photoPreview.innerHTML = '';

        if (this.files.length > 5) {
            alert('Maksimal 5 foto yang dapat diupload');
            this.value = '';
            return;
        }

        Array.from(this.files).forEach((file, index) => {
            if (file.size > 2048000) { // 2MB
                alert(`File ${file.name} terlalu besar. Maksimal 2MB per file.`);
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'img-thumbnail me-2 mb-2';
                img.style = 'width: 80px; height: 80px; object-fit: cover;';
                photoPreview.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    });

    // Form validation
    const form = document.getElementById('returnForm');
    const submitBtn = document.getElementById('submitBtn');

    form.addEventListener('submit', function(e) {
        const deskripsi = document.getElementById('deskripsi_keluhan').value;

        if (deskripsi.length < 20) {
            e.preventDefault();
            alert('Deskripsi keluhan minimal 20 karakter');
            return;
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sedang Mengirim...';
    });

    // Character counter for description
    const descTextarea = document.getElementById('deskripsi_keluhan');
    const charInfo = document.createElement('div');
    charInfo.className = 'form-text';
    charInfo.id = 'charCounter';
    descTextarea.parentNode.appendChild(charInfo);

    function updateCharCount() {
        const current = descTextarea.value.length;
        const min = 20;
        charInfo.textContent = `${current} karakter (minimal ${min} karakter)`;
        charInfo.className = current >= min ? 'form-text text-success' : 'form-text text-danger';
    }

    descTextarea.addEventListener('input', updateCharCount);
    updateCharCount();
});
</script>
@endpush

@push('styles')
<style>
    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .form-label {
        font-weight: 600;
        color: #495057;
    }

    .text-danger {
        font-weight: 600;
    }

    .btn-lg {
        padding: 0.75rem 2rem;
        font-weight: 600;
    }

    .img-thumbnail {
        border: 2px solid #dee2e6;
    }

    .alert {
        border-left: 4px solid;
    }

    .alert-info {
        border-left-color: #0dcaf0;
    }
</style>
@endpush
