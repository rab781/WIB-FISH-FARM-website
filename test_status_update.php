<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Pesanan;

echo "Testing Pesanan status update...\n";

$pesanan = Pesanan::first();
if ($pesanan) {
    echo "Testing status update for pesanan ID: {$pesanan->id_pesanan}\n";
    echo "Current status: {$pesanan->status_pesanan}\n";

    try {
        $pesanan->update([
            'status_pesanan' => 'Dikirim',
            'no_resi' => 'TEST123456',
            'tanggal_pengiriman' => now()
        ]);
        echo "Update successful!\n";
        echo "New status: " . $pesanan->fresh()->status_pesanan . "\n";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
} else {
    echo "No pesanan found\n";
}
