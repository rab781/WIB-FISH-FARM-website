# Refund and Pengembalian System Consolidation

## Overview

This document describes the consolidation of the `refund` and `pengembalian` (returns) functionalities into a single unified system. Previously, the application had two overlapping systems for handling returns and refunds, which caused confusion. The consolidation uses only the `pengembalian` system and eliminates the redundant `refund` system.

## Changes Made

### 1. Route Changes

- All refund routes have been redirected to corresponding pengembalian routes
- Admin refund routes have been replaced with admin pengembalian routes
- Old customer.refunds.* routes now redirect to pengembalian.* routes

### 2. Model Changes

- The `Pesanan` model's `refundRequests()` relationship now uses the `Pengembalian` model instead of `RefundRequest`
- The `canRequestRefund()` method has been updated to check `pengembalian` status
- The `requestRefund()` method has been commented out as it's been replaced by the pengembalian functionality

### 3. Controller Changes

- `PengembalianController` now includes all functionality previously in `RefundController`
- `Admin\PengembalianController` handles all admin functionality for returns/refunds
- Timeline management has been migrated to use the pengembalian system

### 4. Database Usage

- The `pengembalian` table is now used for all return/refund requests
- The `RefundRequest` model is no longer being actively used
- The `status_refund` column in the `pesanan` table is still used but populated by the pengembalian system

## Implementation Details

### Database Structure

The pengembalian table includes:
- id_pengembalian
- id_pesanan
- user_id
- jenis_keluhan
- deskripsi_masalah
- foto_bukti
- jumlah_klaim
- nama_bank
- nomor_rekening
- nama_pemilik_rekening
- status_pengembalian
- catatan_admin
- reviewed_by
- tanggal_review
- tanggal_pengembalian_dana
- nomor_transaksi_pengembalian

### Status Mapping

RefundRequest status values have been mapped to Pengembalian status values:
- 'pending' → 'Menunggu Review'
- 'reviewing' → 'Dalam Review'
- 'approved' → 'Disetujui'
- 'rejected' → 'Ditolak'
- 'processed' → 'Dana Dikembalikan'
- 'completed' → 'Selesai'

## Further Considerations

- In the future, consider updating the database schema to remove the refund-specific columns
- User education may be needed to ensure they understand that all returns and refunds are handled through the same system
- Review any remaining references to RefundRequest in other parts of the codebase that might have been missed
