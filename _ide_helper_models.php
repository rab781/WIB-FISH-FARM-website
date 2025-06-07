<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * 
 *
 * @property int $id ID from RajaOngkir API
 * @property string $provinsi
 * @property string $kabupaten
 * @property string|null $kecamatan
 * @property string|null $tipe Type like "Kota" or "Kabupaten"
 * @property string|null $kode_pos
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ongkir> $ongkir
 * @property-read int|null $ongkir_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alamat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alamat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alamat query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alamat whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alamat whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alamat whereKabupaten($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alamat whereKecamatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alamat whereKodePos($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alamat whereProvinsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alamat whereTipe($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Alamat whereUpdatedAt($value)
 */
	class Alamat extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id_pesanan
 * @property int $id_Produk
 * @property int|null $ukuran_id
 * @property int $kuantitas
 * @property string $harga
 * @property string $subtotal
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Pesanan $pesanan
 * @property-read \App\Models\Produk $produk
 * @property-read \App\Models\ProdukUkuran|null $produk_ukuran
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailPesanan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailPesanan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailPesanan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailPesanan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailPesanan whereHarga($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailPesanan whereIdPesanan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailPesanan whereIdProduk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailPesanan whereKuantitas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailPesanan whereSubtotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailPesanan whereUkuranId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DetailPesanan whereUpdatedAt($value)
 */
	class DetailPesanan extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $category
 * @property string $description
 * @property numeric $amount
 * @property string $expense_date
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereExpenseDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense withoutTrashed()
 */
	class Expense extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Kecamatan> $kecamatan
 * @property-read int|null $kecamatan_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ongkir> $ongkir
 * @property-read int|null $ongkir_count
 * @property-read \App\Models\Provinsi|null $provinsi
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kabupaten newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kabupaten newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kabupaten query()
 */
	class Kabupaten extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property-read \App\Models\Kabupaten|null $kabupaten
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kecamatan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kecamatan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kecamatan query()
 */
	class Kecamatan extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property string $jenis_keluhan
 * @property string $keluhan
 * @property string|null $gambar
 * @property string $status
 * @property string|null $respon_admin
 * @property \Illuminate\Support\Carbon|null $respon_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Keluhan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Keluhan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Keluhan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Keluhan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Keluhan whereGambar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Keluhan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Keluhan whereJenisKeluhan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Keluhan whereKeluhan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Keluhan whereResponAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Keluhan whereResponAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Keluhan whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Keluhan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Keluhan whereUserId($value)
 */
	class Keluhan extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id_keranjang
 * @property int $user_id
 * @property int $id_Produk
 * @property int|null $ukuran_id
 * @property int $jumlah
 * @property string $total_harga
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Produk $produk
 * @property-read \App\Models\ProdukUkuran|null $ukuran
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Keranjang newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Keranjang newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Keranjang query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Keranjang whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Keranjang whereIdKeranjang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Keranjang whereIdProduk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Keranjang whereJumlah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Keranjang whereTotalHarga($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Keranjang whereUkuranId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Keranjang whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Keranjang whereUserId($value)
 */
	class Keranjang extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $type
 * @property string $title
 * @property string $message
 * @property array<array-key, mixed>|null $data
 * @property bool $is_read
 * @property bool $for_admin
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereForAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereIsRead($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereUserId($value)
 */
	class Notification extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id_ongkir
 * @property int $alamat_id
 * @property string $biaya
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Alamat $alamat
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ongkir newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ongkir newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ongkir query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ongkir whereAlamatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ongkir whereBiaya($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ongkir whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ongkir whereIdOngkir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ongkir whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ongkir whereUpdatedAt($value)
 */
	class Ongkir extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property-read \App\Models\User|null $creator
 * @property-read string $icon
 * @property-read string $status_color
 * @property-read \App\Models\Pesanan|null $pesanan
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderTimeline newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderTimeline newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderTimeline query()
 */
	class OrderTimeline extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id_pembayaran
 * @property int $id_pesanan
 * @property bool $status_pembayaran
 * @property string $nomor_rekening
 * @property string $nama_bank
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Pesanan $pesanan
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembayaran newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembayaran newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembayaran query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembayaran whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembayaran whereIdPembayaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembayaran whereIdPesanan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembayaran whereNamaBank($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembayaran whereNomorRekening($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembayaran whereStatusPembayaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pembayaran whereUpdatedAt($value)
 */
	class Pembayaran extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id_pesanan
 * @property int $user_id
 * @property int $id_ongkir
 * @property int|null $alamat_id
 * @property string $kurir
 * @property string $kurir_service
 * @property numeric $ongkir_biaya
 * @property numeric $total_harga
 * @property string $status_pesanan
 * @property string|null $bukti_pembayaran
 * @property string|null $alamat_pengiriman
 * @property string|null $metode_pembayaran
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $batas_waktu
 * @property int|null $berat_total Berat total pesanan dalam gram
 * @property int|null $jumlah_box Jumlah box pengiriman ikan (3 ikan per box)
 * @property \Illuminate\Support\Carbon|null $karantina_mulai Tanggal mulai karantina 7 hari
 * @property \Illuminate\Support\Carbon|null $karantina_selesai Tanggal selesai karantina
 * @property bool $is_karantina_active Status karantina aktif
 * @property string $status_refund
 * @property string|null $alasan_refund Alasan permintaan refund
 * @property string|null $bukti_kerusakan Upload bukti kerusakan produk
 * @property string|null $catatan_admin_refund Catatan admin untuk refund
 * @property \Illuminate\Support\Carbon|null $tanggal_refund_request Tanggal permintaan refund
 * @property \Illuminate\Support\Carbon|null $tanggal_refund_processed Tanggal refund diproses
 * @property numeric|null $jumlah_refund Jumlah refund yang disetujui
 * @property string|null $no_resi Nomor resi pengiriman
 * @property \Illuminate\Support\Carbon|null $tanggal_pengiriman Tanggal pengiriman
 * @property \Illuminate\Support\Carbon|null $tanggal_diterima Tanggal pesanan diterima customer
 * @property array<array-key, mixed>|null $tracking_history History tracking dari TIKI API
 * @property string $kondisi_diterima
 * @property string|null $catatan_penerimaan Catatan saat penerimaan
 * @property bool $is_reviewable Apakah bisa direview
 * @property string|null $alasan_pembatalan Alasan pembatalan pesanan
 * @property-read \App\Models\QuarantineLog|null $activeQuarantineLog
 * @property-read \App\Models\Alamat|null $alamat
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DetailPesanan> $detailPesanan
 * @property-read int|null $detail_pesanan_count
 * @property-read string $formatted_total_harga
 * @property-read bool $is_eligible_for_return
 * @property-read bool $is_trackable
 * @property-read string $status_badge
 * @property-read string $status_color
 * @property-read int $total_items
 * @property-read mixed $ulasan
 * @property-read \App\Models\Ongkir|null $ongkir
 * @property-read \App\Models\Pembayaran|null $pembayaran
 * @property-read \App\Models\QuarantineLog|null $quarantineLog
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RefundRequest> $refundRequests
 * @property-read int|null $refund_requests_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderTimeline> $timeline
 * @property-read int|null $timeline_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesanan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesanan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesanan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesanan whereAlamatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesanan whereAlamatPengiriman($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesanan whereAlasanPembatalan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesanan whereAlasanRefund($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesanan whereBatasWaktu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesanan whereBeratTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesanan whereBuktiKerusakan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesanan whereBuktiPembayaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesanan whereCatatanAdminRefund($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesanan whereCatatanPenerimaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesanan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesanan whereIdOngkir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesanan whereIdPesanan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesanan whereIsKarantinaActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesanan whereIsReviewable($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesanan whereJumlahBox($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesanan whereJumlahRefund($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesanan whereKarantinaMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesanan whereKarantinaSelesai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesanan whereKondisiDiterima($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesanan whereKurir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesanan whereKurirService($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesanan whereMetodePembayaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesanan whereNoResi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesanan whereOngkirBiaya($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesanan whereStatusPesanan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesanan whereStatusRefund($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesanan whereTanggalDiterima($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesanan whereTanggalPengiriman($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesanan whereTanggalRefundProcessed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesanan whereTanggalRefundRequest($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesanan whereTotalHarga($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesanan whereTrackingHistory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesanan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pesanan whereUserId($value)
 */
	class Pesanan extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id_Produk
 * @property string $nama_ikan
 * @property string $deskripsi
 * @property int $stok
 * @property string $harga
 * @property string $jenis_ikan
 * @property string|null $gambar
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DetailPesanan> $detailPesanan
 * @property-read int|null $detail_pesanan_count
 * @property-read mixed $available_sizes
 * @property-read mixed $order_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Keranjang> $keranjang
 * @property-read int|null $keranjang_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProdukUkuran> $ukuran
 * @property-read int|null $ukuran_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ulasan> $ulasan
 * @property-read int|null $ulasan_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Produk newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Produk newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Produk onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Produk orderByPopularity($direction = 'desc')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Produk query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Produk whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Produk whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Produk whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Produk whereGambar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Produk whereHarga($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Produk whereIdProduk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Produk whereJenisIkan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Produk whereNamaIkan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Produk whereStok($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Produk whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Produk withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Produk withoutTrashed()
 */
	class Produk extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $id_produk
 * @property string $ukuran
 * @property int $stok
 * @property string|null $harga
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Produk $produk
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProdukUkuran active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProdukUkuran inStock()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProdukUkuran newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProdukUkuran newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProdukUkuran query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProdukUkuran whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProdukUkuran whereHarga($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProdukUkuran whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProdukUkuran whereIdProduk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProdukUkuran whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProdukUkuran whereStok($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProdukUkuran whereUkuran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProdukUkuran whereUpdatedAt($value)
 */
	class ProdukUkuran extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Kabupaten> $kabupaten
 * @property-read int|null $kabupaten_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provinsi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provinsi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Provinsi query()
 */
	class Provinsi extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property-read int $days_remaining
 * @property-read int $progress_percentage
 * @property-read string $status_badge
 * @property-read array|null $today_check
 * @property-read \App\Models\Pesanan|null $pesanan
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuarantineLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuarantineLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QuarantineLog query()
 */
	class QuarantineLog extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $id_pesanan
 * @property string $jenis_refund
 * @property string $deskripsi_masalah
 * @property array<array-key, mixed>|null $bukti_pendukung Array path file bukti
 * @property string $status
 * @property string|null $catatan_admin
 * @property numeric $jumlah_diminta
 * @property numeric|null $jumlah_disetujui
 * @property string|null $metode_refund Bank transfer, etc
 * @property string|null $detail_refund Detail rekening atau metode refund
 * @property \Illuminate\Support\Carbon|null $reviewed_at
 * @property \Illuminate\Support\Carbon|null $processed_at
 * @property int|null $reviewed_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $jenis_refund_text
 * @property-read string $status_badge
 * @property-read \App\Models\Pesanan $pesanan
 * @property-read \App\Models\User|null $reviewer
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundRequest whereBuktiPendukung($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundRequest whereCatatanAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundRequest whereDeskripsiMasalah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundRequest whereDetailRefund($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundRequest whereIdPesanan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundRequest whereJenisRefund($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundRequest whereJumlahDiminta($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundRequest whereJumlahDisetujui($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundRequest whereMetodeRefund($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundRequest whereProcessedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundRequest whereReviewedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundRequest whereReviewedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundRequest whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundRequest whereUpdatedAt($value)
 */
	class RefundRequest extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property int $ulasan_id
 * @property string $interaction_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Ulasan $ulasan
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReviewInteraction helpful()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReviewInteraction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReviewInteraction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReviewInteraction notHelpful()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReviewInteraction query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReviewInteraction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReviewInteraction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReviewInteraction whereInteractionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReviewInteraction whereUlasanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReviewInteraction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReviewInteraction whereUserId($value)
 */
	class ReviewInteraction extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id_ulasan
 * @property int $user_id
 * @property int $id_Produk
 * @property string $rating
 * @property string|null $komentar
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $balasan_admin Balasan dari admin
 * @property \Illuminate\Support\Carbon|null $tanggal_balasan Tanggal balasan admin
 * @property int|null $admin_reply_by
 * @property bool $is_verified_purchase Apakah pembelian terverifikasi
 * @property array<array-key, mixed>|null $foto_review Foto-foto review produk
 * @property string $status_review
 * @property bool $is_helpful Apakah review membantu
 * @property int $helpful_count Jumlah yang menganggap helpful
 * @property-read \App\Models\User|null $adminReplier
 * @property-read array $photo_urls
 * @property-read string $rating_stars
 * @property-read string $status_badge
 * @property-read string $verification_badge
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ReviewInteraction> $helpfulInteractions
 * @property-read int|null $helpful_interactions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ReviewInteraction> $interactions
 * @property-read int|null $interactions_count
 * @property-read \App\Models\Produk $produk
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ulasan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ulasan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ulasan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ulasan whereAdminReplyBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ulasan whereBalasanAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ulasan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ulasan whereFotoReview($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ulasan whereHelpfulCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ulasan whereIdProduk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ulasan whereIdUlasan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ulasan whereIsHelpful($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ulasan whereIsVerifiedPurchase($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ulasan whereKomentar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ulasan whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ulasan whereStatusReview($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ulasan whereTanggalBalasan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ulasan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ulasan whereUserId($value)
 */
	class Ulasan extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string|null $google_id
 * @property string|null $google_token
 * @property string|null $google_refresh_token
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property bool $is_admin
 * @property string|null $remember_token
 * @property string|null $foto
 * @property string|null $avatar
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $alamat_jalan
 * @property string|null $no_hp
 * @property int|null $alamat_id
 * @property-read \App\Models\Alamat|null $alamat
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Keluhan> $keluhan
 * @property-read int|null $keluhan_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Keranjang> $keranjang
 * @property-read int|null $keranjang_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Notification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pesanan> $pesanan
 * @property-read int|null $pesanan_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ulasan> $ulasan
 * @property-read int|null $ulasan_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAlamatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAlamatJalan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereGoogleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereGoogleRefreshToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereGoogleToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereNoHp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

