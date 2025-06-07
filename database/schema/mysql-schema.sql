/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `alamat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `alamat` (
  `id` bigint unsigned NOT NULL COMMENT 'ID from RajaOngkir API',
  `provinsi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kabupaten` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kecamatan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipe` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Type like "Kota" or "Kabupaten"',
  `kode_pos` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `detail_pesanan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detail_pesanan` (
  `id_pesanan` bigint unsigned NOT NULL,
  `id_Produk` bigint unsigned NOT NULL,
  `ukuran_id` bigint unsigned DEFAULT NULL,
  `kuantitas` int NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_pesanan`,`id_Produk`),
  KEY `detail_pesanan_id_produk_foreign` (`id_Produk`),
  KEY `detail_pesanan_ukuran_id_foreign` (`ukuran_id`),
  CONSTRAINT `detail_pesanan_id_pesanan_foreign` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id_pesanan`) ON DELETE CASCADE,
  CONSTRAINT `detail_pesanan_id_produk_foreign` FOREIGN KEY (`id_Produk`) REFERENCES `produk` (`id_Produk`),
  CONSTRAINT `detail_pesanan_ukuran_id_foreign` FOREIGN KEY (`ukuran_id`) REFERENCES `produk_ukuran` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `expenses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `expenses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `expense_date` date NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `keluhans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `keluhans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `jenis_keluhan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keluhan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `gambar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('Belum Diproses','Sedang Diproses','Selesai') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Belum Diproses',
  `respon_admin` text COLLATE utf8mb4_unicode_ci,
  `respon_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `keluhans_user_id_foreign` (`user_id`),
  CONSTRAINT `keluhans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `keranjang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `keranjang` (
  `id_keranjang` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `id_Produk` bigint unsigned NOT NULL,
  `ukuran_id` bigint unsigned DEFAULT NULL,
  `jumlah` int NOT NULL,
  `total_harga` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_keranjang`),
  KEY `keranjang_user_id_foreign` (`user_id`),
  KEY `keranjang_id_produk_foreign` (`id_Produk`),
  KEY `keranjang_ukuran_id_foreign` (`ukuran_id`),
  CONSTRAINT `keranjang_id_produk_foreign` FOREIGN KEY (`id_Produk`) REFERENCES `produk` (`id_Produk`),
  CONSTRAINT `keranjang_ukuran_id_foreign` FOREIGN KEY (`ukuran_id`) REFERENCES `produk_ukuran` (`id`) ON DELETE SET NULL,
  CONSTRAINT `keranjang_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` json DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `for_admin` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_user_id_foreign` (`user_id`),
  CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ongkir`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ongkir` (
  `id_ongkir` bigint unsigned NOT NULL AUTO_INCREMENT,
  `alamat_id` bigint unsigned NOT NULL,
  `biaya` decimal(10,2) NOT NULL DEFAULT '50000.00',
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_ongkir`),
  KEY `ongkir_alamat_id_foreign` (`alamat_id`),
  CONSTRAINT `ongkir_alamat_id_foreign` FOREIGN KEY (`alamat_id`) REFERENCES `alamat` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `order_timeline`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_timeline` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_pesanan` bigint unsigned NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `metadata` json DEFAULT NULL COMMENT 'Additional data like tracking info',
  `is_customer_visible` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_timeline_created_by_foreign` (`created_by`),
  KEY `order_timeline_id_pesanan_foreign` (`id_pesanan`),
  CONSTRAINT `order_timeline_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `order_timeline_id_pesanan_foreign` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id_pesanan`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pembayaran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pembayaran` (
  `id_pembayaran` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_pesanan` bigint unsigned NOT NULL,
  `status_pembayaran` tinyint(1) NOT NULL,
  `nomor_rekening` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_bank` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_pembayaran`),
  KEY `pembayaran_id_pesanan_foreign` (`id_pesanan`),
  CONSTRAINT `pembayaran_id_pesanan_foreign` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id_pesanan`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pengembalian`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pengembalian` (
  `id_pengembalian` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_pesanan` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `jenis_keluhan` enum('Barang Rusak','Barang Tidak Sesuai','Barang Kurang','Kualitas Buruk','Lainnya') COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi_masalah` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto_bukti` json DEFAULT NULL,
  `jumlah_klaim` decimal(10,2) NOT NULL,
  `nama_bank` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nomor_rekening` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_pemilik_rekening` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_pengembalian` enum('Menunggu Review','Dalam Review','Disetujui','Ditolak','Dana Dikembalikan','Selesai') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Menunggu Review',
  `catatan_admin` text COLLATE utf8mb4_unicode_ci,
  `reviewed_by` bigint unsigned DEFAULT NULL,
  `tanggal_review` timestamp NULL DEFAULT NULL,
  `tanggal_pengembalian_dana` timestamp NULL DEFAULT NULL,
  `nomor_transaksi_pengembalian` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_pengembalian`),
  KEY `pengembalian_reviewed_by_foreign` (`reviewed_by`),
  KEY `pengembalian_user_id_status_pengembalian_index` (`user_id`,`status_pengembalian`),
  KEY `pengembalian_id_pesanan_index` (`id_pesanan`),
  CONSTRAINT `pengembalian_id_pesanan_foreign` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id_pesanan`) ON DELETE CASCADE,
  CONSTRAINT `pengembalian_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pengembalian_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pesanan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pesanan` (
  `id_pesanan` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `id_ongkir` bigint unsigned NOT NULL,
  `alamat_id` bigint unsigned DEFAULT NULL,
  `kurir` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'tiki',
  `kurir_service` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'REG',
  `ongkir_biaya` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_harga` decimal(10,2) NOT NULL,
  `status_pesanan` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'Menunggu Pembayaran',
  `bukti_pembayaran` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat_pengiriman` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `metode_pembayaran` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `batas_waktu` timestamp NULL DEFAULT NULL,
  `berat_total` int unsigned DEFAULT NULL COMMENT 'Berat total pesanan dalam gram',
  `jumlah_box` int unsigned DEFAULT NULL COMMENT 'Jumlah box pengiriman ikan (3 ikan per box)',
  `karantina_mulai` timestamp NULL DEFAULT NULL COMMENT 'Tanggal mulai karantina 7 hari',
  `karantina_selesai` timestamp NULL DEFAULT NULL COMMENT 'Tanggal selesai karantina',
  `is_karantina_active` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Status karantina aktif',
  `status_refund` enum('none','requested','approved','rejected','processed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'none',
  `alasan_refund` text COLLATE utf8mb4_unicode_ci COMMENT 'Alasan permintaan refund',
  `bukti_kerusakan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Upload bukti kerusakan produk',
  `catatan_admin_refund` text COLLATE utf8mb4_unicode_ci COMMENT 'Catatan admin untuk refund',
  `tanggal_refund_request` timestamp NULL DEFAULT NULL COMMENT 'Tanggal permintaan refund',
  `tanggal_refund_processed` timestamp NULL DEFAULT NULL COMMENT 'Tanggal refund diproses',
  `jumlah_refund` decimal(10,2) DEFAULT NULL COMMENT 'Jumlah refund yang disetujui',
  `no_resi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nomor resi pengiriman',
  `tanggal_pengiriman` timestamp NULL DEFAULT NULL COMMENT 'Tanggal pengiriman',
  `tanggal_diterima` timestamp NULL DEFAULT NULL COMMENT 'Tanggal pesanan diterima customer',
  `tracking_history` json DEFAULT NULL COMMENT 'History tracking dari TIKI API',
  `kondisi_diterima` enum('baik','rusak','belum_dikonfirmasi') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum_dikonfirmasi',
  `catatan_penerimaan` text COLLATE utf8mb4_unicode_ci COMMENT 'Catatan saat penerimaan',
  `is_reviewable` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Apakah bisa direview',
  `alasan_pembatalan` text COLLATE utf8mb4_unicode_ci COMMENT 'Alasan pembatalan pesanan',
  `tanggal_pembayaran` timestamp NULL DEFAULT NULL,
  `tanggal_selesai` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_pesanan`),
  KEY `pesanan_user_id_foreign` (`user_id`),
  KEY `pesanan_alamat_id_foreign` (`alamat_id`),
  CONSTRAINT `pesanan_alamat_id_foreign` FOREIGN KEY (`alamat_id`) REFERENCES `alamat` (`id`),
  CONSTRAINT `pesanan_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `produk`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `produk` (
  `id_Produk` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_ikan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `stok` int NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `jenis_ikan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `gambar` blob,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_Produk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `produk_ukuran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `produk_ukuran` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_produk` bigint unsigned NOT NULL,
  `ukuran` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `stok` int NOT NULL DEFAULT '0',
  `harga` decimal(10,2) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `produk_ukuran_id_produk_foreign` (`id_produk`),
  CONSTRAINT `produk_ukuran_id_produk_foreign` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_Produk`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `produk_ukurans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `produk_ukurans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `refund_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `refund_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_pesanan` bigint unsigned NOT NULL,
  `jenis_refund` enum('kerusakan','keterlambatan','tidak_sesuai','kematian_ikan','lainnya') COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi_masalah` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `bukti_pendukung` json DEFAULT NULL COMMENT 'Array path file bukti',
  `status` enum('pending','reviewing','approved','rejected','processed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `catatan_admin` text COLLATE utf8mb4_unicode_ci,
  `jumlah_diminta` decimal(10,2) NOT NULL,
  `jumlah_disetujui` decimal(10,2) DEFAULT NULL,
  `metode_refund` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Bank transfer, etc',
  `detail_refund` text COLLATE utf8mb4_unicode_ci COMMENT 'Detail rekening atau metode refund',
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `processed_at` timestamp NULL DEFAULT NULL,
  `reviewed_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `refund_requests_reviewed_by_foreign` (`reviewed_by`),
  KEY `refund_requests_id_pesanan_foreign` (`id_pesanan`),
  CONSTRAINT `refund_requests_id_pesanan_foreign` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id_pesanan`) ON DELETE CASCADE,
  CONSTRAINT `refund_requests_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `review_interactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `review_interactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `ulasan_id` bigint unsigned NOT NULL,
  `interaction_type` enum('helpful','not_helpful') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `review_interactions_user_id_ulasan_id_unique` (`user_id`,`ulasan_id`),
  KEY `review_interactions_ulasan_id_foreign` (`ulasan_id`),
  CONSTRAINT `review_interactions_ulasan_id_foreign` FOREIGN KEY (`ulasan_id`) REFERENCES `ulasan` (`id_ulasan`) ON DELETE CASCADE,
  CONSTRAINT `review_interactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ulasan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ulasan` (
  `id_ulasan` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `id_Produk` bigint unsigned NOT NULL,
  `rating` decimal(3,1) NOT NULL,
  `komentar` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `balasan_admin` text COLLATE utf8mb4_unicode_ci COMMENT 'Balasan dari admin',
  `tanggal_balasan` timestamp NULL DEFAULT NULL COMMENT 'Tanggal balasan admin',
  `admin_reply_by` bigint unsigned DEFAULT NULL,
  `is_verified_purchase` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Apakah pembelian terverifikasi',
  `foto_review` json DEFAULT NULL COMMENT 'Foto-foto review produk',
  `status_review` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'approved',
  `is_helpful` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Apakah review membantu',
  `helpful_count` int NOT NULL DEFAULT '0' COMMENT 'Jumlah yang menganggap helpful',
  PRIMARY KEY (`id_ulasan`),
  KEY `ulasan_user_id_foreign` (`user_id`),
  KEY `ulasan_id_produk_foreign` (`id_Produk`),
  KEY `ulasan_admin_reply_by_foreign` (`admin_reply_by`),
  CONSTRAINT `ulasan_admin_reply_by_foreign` FOREIGN KEY (`admin_reply_by`) REFERENCES `users` (`id`),
  CONSTRAINT `ulasan_id_produk_foreign` FOREIGN KEY (`id_Produk`) REFERENCES `produk` (`id_Produk`),
  CONSTRAINT `ulasan_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `google_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_refresh_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `alamat_jalan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `no_hp` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_alamat_id_foreign` (`alamat_id`),
  CONSTRAINT `users_alamat_id_foreign` FOREIGN KEY (`alamat_id`) REFERENCES `alamat` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1,'0001_01_01_000000_create_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (2,'0001_01_01_000001_create_cache_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (3,'2025_04_19_000001_create_alamat_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (4,'2025_04_19_143224_create_provinsi_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (5,'2025_04_19_143230_create_kabupaten_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (6,'2025_04_19_143240_create_kecamatan_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (7,'2025_04_20_063916_create_produk_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (8,'2025_04_20_072138_create_keranjang_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (9,'2025_04_20_072220_create_pesanan_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (10,'2025_04_20_072630_create_detail_pesanan_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (11,'2025_04_20_072754_create_pembayaran_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (12,'2025_04_20_072854_create_ulasan_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (13,'2025_04_20_073707_add_foreign_keys_to_detail_pesanan_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (14,'2025_04_20_073732_add_foreign_keys_to_pembayaran_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (15,'2025_04_20_081936_add_deleted_at_to_produk_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (16,'2025_04_21_143252_add_address_and_phone_to_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (17,'2025_04_21_152009_fix_users_address_columns',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (18,'2025_04_22_085612_add_google_oauth_columns_to_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (19,'2025_04_30_162534_create_produk_ukurans_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (20,'2025_04_30_221632_create_produk_ukuran_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (21,'2025_04_30_222517_add_ukuran_id_to_keranjang',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (22,'2025_05_01_000203_create_notifications_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (23,'2025_05_11_100303_add_missing_location_fields_to_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (24,'2025_05_18_051610_create_ongkir_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (25,'2025_05_18_062714_add_alamat_and_metode_to_pesanan_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (26,'2025_05_18_063114_add_new_columns_to_detail_pesanan_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (27,'2025_05_18_064153_add_batas_waktu_to_pesanan_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (28,'2025_05_18_065839_update_status_pesanan_enum_values',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (29,'2025_05_20_000002_modify_users_table_for_alamat',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (30,'2025_05_20_131831_drop_provinsi_kabupaten_kecamatan_tables',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (31,'2025_05_20_132224_update_foreign_keys_for_new_alamat_structure',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (32,'2025_05_20_132913_fix_table_relationships_for_alamat_structure',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (33,'2025_05_21_122931_add_alamat_id_to_pesanan_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (34,'2025_05_23_132135_add_jumlah_box_to_pesanan_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (35,'2025_05_24_114351_add_shipping_columns_to_pesanan_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (36,'2025_05_24_114700_add_bukti_pembayaran_to_pesanan_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (37,'2024_01_01_000000_create_expenses_table',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (38,'2025_01_20_000000_enhance_order_management_system',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (39,'2025_05_24_000001_create_consolidated_schema',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (40,'2025_05_29_114234_fix_enhancement_migration',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (41,'2025_06_06_160030_create_keluhans_table',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (42,'2025_06_07_045056_add_missing_columns_to_pesanan_table',5);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (43,'2025_06_07_120000_fix_status_pesanan_enum',6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (44,'2025_06_07_121000_change_status_to_varchar',7);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (45,'2025_06_07_130000_create_pengembalian_table',8);
