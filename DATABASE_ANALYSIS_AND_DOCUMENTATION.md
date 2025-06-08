# 📊 ANALISIS DATABASE - SISTEM MANAJEMEN TOKO IKAN ONLINE

## 🎯 RINGKASAN EKSEKUTIF

Sistem database ini merupakan implementasi lengkap untuk platform e-commerce penjualan ikan dengan fitur-fitur modern seperti manajemen pesanan, sistem review, pengembalian dana, dan notifikasi real-time.

---

## 📋 DAFTAR TABEL & STRUKTUR

### 1. **TABEL INTI (CORE TABLES)**

#### 1.1 `users` - Manajemen Pengguna
```sql
- id (Primary Key)
- name, email, password (Auth essentials)
- is_admin (Role management)
- foto, avatar (Profile images)
- no_hp (Contact info)
- google_id, google_token, google_refresh_token (OAuth)
- timestamps
```
**Fungsi**: Pusat manajemen autentikasi dan profil pengguna

#### 1.2 `produk` - Katalog Produk
```sql
- id_Produk (Primary Key)
- nama_ikan, deskripsi, jenis_ikan
- stok, harga
- gambar (Product images)
- soft_deletes (Archive capability)
- timestamps
```
**Fungsi**: Manajemen inventory dan katalog produk

#### 1.3 `alamat` - Data Alamat
```sql
- id (Primary Key - RajaOngkir API compatible)
- provinsi, kabupaten, kecamatan
- tipe, kode_pos
- alamat_jalan
- timestamps
```
**Fungsi**: Integrasi dengan sistem pengiriman

### 2. **TABEL TRANSAKSI (TRANSACTION TABLES)**

#### 2.1 `pesanan` - Order Management
```sql
- id_pesanan (Primary Key)
- user_id, alamat_id (Foreign Keys)
- kurir, kurir_service, ongkir_biaya (Shipping)
- metode_pembayaran, total_harga, bukti_pembayaran
- status_pesanan (VARCHAR - supports Indonesian statuses)
- batas_waktu, jumlah_box
- 
ENHANCED FIELDS:
- karantina_mulai, karantina_selesai, is_karantina_active
- no_resi, tanggal_pengiriman, tanggal_diterima
- tracking_history (JSON)
- kondisi_diterima, catatan_penerimaan
- tanggal_pembayaran, tanggal_selesai
- alasan_pembatalan, berat_total
- is_reviewable
- timestamps
```
**Fungsi**: Pusat manajemen pesanan dengan tracking lengkap

#### 2.2 `detail_pesanan` - Order Items
```sql
- id_pesanan, id_Produk (Composite Primary Key)
- kuantitas, harga, subtotal
- timestamps
```
**Fungsi**: Detail item dalam setiap pesanan

#### 2.3 `keranjang` - Shopping Cart
```sql
- id_keranjang (Primary Key)
- user_id, id_Produk (Foreign Keys)
- jumlah, total_harga
- timestamps
```
**Fungsi**: Temporary storage untuk belanja

#### 2.4 `pembayaran` - Payment Records
```sql
- id_pembayaran (Primary Key)
- id_pesanan (Foreign Key)
- status_pembayaran, nomor_rekening, nama_bank
- timestamps
```
**Fungsi**: Tracking pembayaran

### 3. **TABEL LAYANAN PELANGGAN (CUSTOMER SERVICE TABLES)**

#### 3.1 `pengembalian` - Return/Refund Management
```sql
- id_pengembalian (Primary Key)
- id_pesanan, user_id (Foreign Keys)
- jenis_keluhan (ENUM)
- deskripsi_masalah, foto_bukti (JSON)
- jumlah_klaim
- nama_bank, nomor_rekening, nama_pemilik_rekening
- status_pengembalian (ENUM)
- catatan_admin, reviewed_by, tanggal_review
- tanggal_pengembalian_dana, nomor_transaksi_pengembalian
- timestamps
```
**Fungsi**: Sistem pengembalian dana yang komprehensif

#### 3.2 `keluhans` - Customer Complaints
```sql
- id (Primary Key)
- user_id (Foreign Key)
- jenis_keluhan, keluhan, gambar
- status (ENUM), respon_admin, respon_at
- timestamps
```
**Fungsi**: Manajemen keluhan pelanggan

#### 3.3 `ulasan` - Product Reviews
```sql
- id_ulasan (Primary Key)
- user_id, id_Produk (Foreign Keys)
- rating, komentar
- 
ENHANCED FIELDS:
- balasan_admin, tanggal_balasan, admin_reply_by
- is_verified_purchase, foto_review (JSON)
- status_review, is_helpful, helpful_count
- timestamps
```
**Fungsi**: Sistem review dengan balasan admin

### 4. **TABEL TRACKING & MONITORING**

#### 4.1 `order_timelines` - Order Status Tracking
```sql
- id (Primary Key)
- id_pesanan (Foreign Key)
- status, title, description
- metadata (JSON), is_customer_visible
- created_by, timestamps
```
**Fungsi**: Detailed order status history

#### 4.2 `notifications` - Real-time Notifications
```sql
- id (Primary Key)
- user_id (Foreign Key, nullable for broadcast)
- type, title, message
- data (JSON), is_read, for_admin
- timestamps
```
**Fungsi**: Sistem notifikasi real-time

#### 4.3 `review_interactions` - Review Engagement
```sql
- id (Primary Key)
- user_id, ulasan_id (Foreign Keys)
- interaction_type (helpful/not_helpful)
- timestamps
- UNIQUE(user_id, ulasan_id)
```
**Fungsi**: Tracking helpful reviews

### 5. **TABEL UTILITAS (UTILITY TABLES)**

#### 5.1 `ongkir` - Shipping Costs
```sql
- id_ongkir (Primary Key)
- alamat_id (Foreign Key)
- biaya (Default: 50000), keterangan
- timestamps
```

#### 5.2 `expenses` - Business Expenses
```sql
- id (Primary Key)
- category, description, amount
- expense_date, notes
- soft_deletes, timestamps
```

#### 5.3 `refund_requests` - Legacy Refund System
**Status**: Deprecated (replaced by `pengembalian`)

---

## 🔗 DIAGRAM RELASI DATABASE

```
users (1) ——————————————— (N) pesanan
  |                           |
  |                           |——————— (1) alamat
  |                           |
  |                           |——————— (N) detail_pesanan ——— (1) produk
  |                           |
  |——————— (N) keranjang ——————————————— (1) produk
  |                           |
  |——————— (N) ulasan ————————————————— (1) produk
  |                           |
  |——————— (N) keluhans        |——————— (N) pengembalian
  |                           |
  |——————— (N) notifications   |——————— (N) order_timelines
                              |
                              |——————— (1) pembayaran
```

---

## ⚠️ MASALAH & REKOMENDASI PERBAIKAN

### 🚨 **MASALAH KRITIS**

1. **Migrasi Database Berantakan**
   - Terdapat 15+ file migrasi yang saling tumpang tindih
   - Banyak migrasi yang mencoba mengubah kolom yang sama berulang kali
   - Status pesanan berubah dari ENUM ke VARCHAR tanpa konsistensi

2. **Inkonsistensi Naming Convention**
   - Mixed: `id_pesanan` vs `user_id` vs `id_Produk`
   - Tidak konsisten antara snake_case dan camelCase

3. **Duplikasi Fungsionalitas**
   - `refund_requests` dan `pengembalian` melakukan hal yang sama
   - Beberapa kolom di tabel `pesanan` yang redundant

### 🔧 **REKOMENDASI PERBAIKAN**

#### 1. **Konsolidasi Migrasi**
```bash
# Buat migrasi fresh untuk produksi
php artisan make:migration create_consolidated_database_schema
```

#### 2. **Standardisasi Naming Convention**
- Gunakan `snake_case` konsisten untuk semua kolom
- Format: `table_id` untuk primary key (contoh: `user_id`, `product_id`)

#### 3. **Cleanup Database Structure**
```sql
-- Drop redundant tables
DROP TABLE IF EXISTS refund_requests;

-- Standardize foreign keys
ALTER TABLE pesanan RENAME COLUMN id_pesanan TO pesanan_id;
ALTER TABLE produk RENAME COLUMN id_Produk TO product_id;
```

#### 4. **Index Optimization**
```sql
-- Performance indexes
CREATE INDEX idx_pesanan_user_status ON pesanan(user_id, status_pesanan);
CREATE INDEX idx_pesanan_created_at ON pesanan(created_at);
CREATE INDEX idx_notifications_user_read ON notifications(user_id, is_read);
```

#### 5. **Data Validation & Constraints**
```sql
-- Add proper constraints
ALTER TABLE pesanan ADD CONSTRAINT chk_total_harga_positive CHECK (total_harga >= 0);
ALTER TABLE produk ADD CONSTRAINT chk_stok_non_negative CHECK (stok >= 0);
ALTER TABLE ulasan ADD CONSTRAINT chk_rating_range CHECK (rating >= 1 AND rating <= 5);
```

---

## 📚 **DOKUMENTASI UNTUK DEVELOPER**

### **Environment Setup**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=toko_ikan_db
DB_USERNAME=root
DB_PASSWORD=
```

### **Key Models & Relationships**
```php
// Primary Models
User::class -> hasMany(Pesanan::class)
Pesanan::class -> hasMany(DetailPesanan::class)
Produk::class -> hasMany(DetailPesanan::class)

// Service Models  
Pengembalian::class -> belongsTo(Pesanan::class)
Ulasan::class -> belongsTo([User::class, Produk::class])
Notification::class -> belongsTo(User::class)
```

### **Database Seeding Priority**
1. Users (admin + sample customers)
2. Alamat (regional data from RajaOngkir)
3. Produk (sample fish products)
4. Pesanan & DetailPesanan (sample orders)
5. Ulasan (sample reviews)

---

## 🎯 **ROADMAP PENGEMBANGAN**

### **Phase 1: Database Cleanup (Week 1-2)**
- [ ] Konsolidasi migrasi
- [ ] Standardisasi naming convention
- [ ] Remove redundant tables
- [ ] Add proper indexes

### **Phase 2: Performance Optimization (Week 3-4)**
- [ ] Query optimization
- [ ] Database indexing
- [ ] Cache layer implementation
- [ ] Database monitoring setup

### **Phase 3: Advanced Features (Week 5-6)**
- [ ] Full-text search untuk produk
- [ ] Advanced analytics tables
- [ ] Audit trail system
- [ ] Backup & recovery procedures

---

## 📊 **STATISTIK DATABASE**

| Kategori | Jumlah | Status |
|----------|--------|--------|
| Total Tables | 20+ | ✅ Complete |
| Core Business Tables | 8 | ✅ Functional |
| Support Tables | 7 | ✅ Functional |
| Migration Files | 15 | ⚠️ Need Cleanup |
| Redundant Tables | 2 | ❌ Need Removal |
| Missing Indexes | 5+ | ⚠️ Need Addition |

---

## 🔐 **SECURITY CONSIDERATIONS**

1. **Data Sensitive**: Encrypt payment info, personal data
2. **Access Control**: Implement proper RBAC
3. **Audit Trail**: Log critical operations
4. **Backup Strategy**: Daily automated backups
5. **SQL Injection**: Use Eloquent ORM consistently

---

*Dokumen ini dibuat untuk memberikan pandangan menyeluruh tentang struktur database sistem toko ikan online. Untuk pertanyaan teknis lebih lanjut, silakan hubungi tim development.*
