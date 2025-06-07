<?php
// Payment image path tester script
// This file is for diagnostic purposes and should be deleted after use

require_once __DIR__ . '/../vendor/autoload.php';

// Configuration
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

// Authentication
// Remove this if you want to test without auth

echo "<h1>Payment Image Path Tester</h1>";
echo "<p>This script checks all the paths where payment proof images might be stored.</p>";

// Show info about directories
$paths = [
    'public/uploads/payments' => public_path('uploads/payments'),
    'storage/app/public/payments' => storage_path('app/public/payments'),
    'storage/app/public/payment_proofs' => storage_path('app/public/payment_proofs'),
];

echo "<h2>Directory Status</h2>";
echo "<table border='1' cellpadding='8'>";
echo "<tr><th>Path</th><th>Exists</th><th>Readable</th><th>Files Count</th></tr>";

foreach ($paths as $name => $path) {
    $exists = is_dir($path);
    $readable = $exists ? is_readable($path) : false;
    $files = $exists ? count(glob($path . '/*.*')) : 0;

    echo "<tr>";
    echo "<td>$name</td>";
    echo "<td>" . ($exists ? '✅ Yes' : '❌ No') . "</td>";
    echo "<td>" . ($readable ? '✅ Yes' : '❌ No') . "</td>";
    echo "<td>$files</td>";
    echo "</tr>";
}
echo "</table>";

// Find some orders with bukti_pembayaran
use App\Models\Pesanan;
$orders = Pesanan::whereNotNull('bukti_pembayaran')->take(5)->get();

if ($orders->isEmpty()) {
    echo "<h2>No orders with payment proofs found</h2>";
    exit;
}

echo "<h2>Sample Orders with Payment Proofs</h2>";
echo "<table border='1' cellpadding='8'>";
echo "<tr><th>Order ID</th><th>Bukti Pembayaran Path</th><th>Image Found At</th><th>Preview</th></tr>";

foreach ($orders as $pesanan) {
    echo "<tr>";
    echo "<td>{$pesanan->id_pesanan}</td>";
    echo "<td>{$pesanan->bukti_pembayaran}</td>";

    // Check all possible paths
    $fileName = basename($pesanan->bukti_pembayaran);
    $foundPath = "Not found";
    $imagePath = "";

    // Try different possible paths
    if (file_exists(public_path('uploads/payments/' . $fileName))) {
        $foundPath = 'public/uploads/payments/' . $fileName;
        $imagePath = '/uploads/payments/' . $fileName;
    } elseif (file_exists(public_path($pesanan->bukti_pembayaran))) {
        $foundPath = 'public/' . $pesanan->bukti_pembayaran;
        $imagePath = '/' . $pesanan->bukti_pembayaran;
    } elseif (file_exists(storage_path('app/public/' . $pesanan->bukti_pembayaran))) {
        $foundPath = 'storage/app/public/' . $pesanan->bukti_pembayaran;
        $imagePath = '/storage/' . $pesanan->bukti_pembayaran;
    } elseif (file_exists(storage_path('app/public/payment_proofs/' . $fileName))) {
        $foundPath = 'storage/app/public/payment_proofs/' . $fileName;
        $imagePath = '/storage/payment_proofs/' . $fileName;
    }

    echo "<td>$foundPath</td>";

    if ($imagePath) {
        echo "<td><img src='$imagePath' style='max-height:100px;'></td>";
    } else {
        echo "<td>Image not found</td>";
    }

    echo "</tr>";
}
echo "</table>";

echo "<h2>Path Builder Test</h2>";
echo "<form method='post' action=''>";
echo "<input type='hidden' name='_token' value='" . csrf_token() . "'>";
echo "Order ID: <input type='text' name='order_id'>";
echo "<input type='submit' value='Test Paths'>";
echo "</form>";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $orderId = $_POST['order_id'];
    $pesanan = Pesanan::find($orderId);

    if (!$pesanan) {
        echo "<p>Order not found</p>";
    } else {
        echo "<h3>Testing paths for Order #{$pesanan->id_pesanan}</h3>";

        if (!$pesanan->bukti_pembayaran) {
            echo "<p>This order has no bukti_pembayaran set</p>";
        } else {
            $fileName = basename($pesanan->bukti_pembayaran);
            echo "<table border='1' cellpadding='8'>";
            echo "<tr><th>Path Type</th><th>Full Path</th><th>Exists</th></tr>";

            $paths = [
                'Direct using bukti_pembayaran' => public_path($pesanan->bukti_pembayaran),
                'Using filename in uploads/payments' => public_path('uploads/payments/' . $fileName),
                'Using storage/app/public' => storage_path('app/public/' . $pesanan->bukti_pembayaran),
                'Using storage/app/public/payment_proofs' => storage_path('app/public/payment_proofs/' . $fileName),
            ];

            foreach ($paths as $type => $path) {
                $exists = file_exists($path);
                echo "<tr>";
                echo "<td>$type</td>";
                echo "<td>$path</td>";
                echo "<td>" . ($exists ? '✅ Yes' : '❌ No') . "</td>";
                echo "</tr>";
            }

            echo "</table>";

            // Test the actual URL that would be generated
            $imageUrl = route('admin.pesanan.payment-proof', ['id' => $pesanan->id_pesanan]);
            echo "<h3>Generated URL Test</h3>";
            echo "<p>URL: $imageUrl</p>";
            echo "<p>Preview:</p>";
            echo "<img src='$imageUrl' style='max-height:300px;'>";
        }
    }
}
