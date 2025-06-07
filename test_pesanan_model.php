<?php
// Test script to verify Pesanan model configuration

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Testing Pesanan Model Configuration ===\n";

$pesanan = new App\Models\Pesanan();
$fillable = $pesanan->getFillable();

echo "1. Checking fillable fields:\n";
echo "   - no_resi in fillable: " . (in_array('no_resi', $fillable) ? 'YES' : 'NO') . "\n";
echo "   - nomor_resi in fillable: " . (in_array('nomor_resi', $fillable) ? 'YES' : 'NO') . "\n";

echo "\n2. All resi-related fillable fields:\n";
foreach($fillable as $field) {
    if(strpos($field, 'resi') !== false) {
        echo "   - $field\n";
    }
}

echo "\n3. Testing accessor method:\n";
try {
    // Test with a sample instance
    $testPesanan = new App\Models\Pesanan();
    $testPesanan->no_resi = 'TEST123456';
    echo "   - no_resi value: " . $testPesanan->no_resi . "\n";
    echo "   - nomor_resi accessor: " . $testPesanan->nomor_resi . "\n";
    echo "   - Accessor working: " . ($testPesanan->nomor_resi === $testPesanan->no_resi ? 'YES' : 'NO') . "\n";
} catch (Exception $e) {
    echo "   - Error testing accessor: " . $e->getMessage() . "\n";
}

echo "\n=== Test Complete ===\n";
