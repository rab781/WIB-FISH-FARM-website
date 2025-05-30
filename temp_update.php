<?php
require 'vendor/autoload.php';
require 'bootstrap/app.php';

$app = Illuminate\Foundation\Application::getInstance();
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$pesanan = App\Models\Pesanan::first();
if($pesanan) {
    $pesanan->bukti_pembayaran = 'storage/payment_proofs/sample.jpg';
    $pesanan->save();
    echo 'Updated pesanan #' . $pesanan->id_pesanan . ' with payment proof' . PHP_EOL;
} else {
    echo 'No pesanan found' . PHP_EOL;
}
