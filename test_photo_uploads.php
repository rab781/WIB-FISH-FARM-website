<?php

/**
 * Test script to verify photo upload functionality
 * Run this with: php artisan tinker < test_photo_uploads.php
 */

echo "=== Photo Upload Functionality Test ===\n";

// Test 1: Check if storage directories exist
echo "\n1. Checking storage directories...\n";

$directories = [
    'storage/app/public/reviews',
    'storage/app/public/pengembalian',
    'storage/app/public/uploads/users',
    'public/storage'
];

foreach ($directories as $dir) {
    $path = base_path($dir);
    if (file_exists($path)) {
        echo "✓ {$dir} exists\n";
    } else {
        echo "✗ {$dir} missing - creating...\n";
        if ($dir === 'public/storage') {
            // Create symlink
            shell_exec('php artisan storage:link');
        } else {
            mkdir($path, 0755, true);
        }
    }
}

// Test 2: Check if model methods work correctly
echo "\n2. Testing model methods...\n";

// Test Ulasan model photo methods
$ulasanWithPhotos = \App\Models\Ulasan::whereNotNull('foto_review')->first();
if ($ulasanWithPhotos) {
    echo "✓ Found review with photos\n";
    echo "  Photos count: " . count($ulasanWithPhotos->photos) . "\n";
    echo "  Has photos: " . ($ulasanWithPhotos->hasPhotos() ? 'Yes' : 'No') . "\n";
    echo "  Photo URLs count: " . count($ulasanWithPhotos->photo_urls) . "\n";
} else {
    echo "- No reviews with photos found\n";
}

// Test Pengembalian model photo handling
$pengembalianWithPhotos = \App\Models\Pengembalian::whereNotNull('foto_bukti')->first();
if ($pengembalianWithPhotos) {
    echo "✓ Found pengembalian with photos\n";
    echo "  Photo data type: " . gettype($pengembalianWithPhotos->foto_bukti) . "\n";
    echo "  Photo count: " . (is_array($pengembalianWithPhotos->foto_bukti) ? count($pengembalianWithPhotos->foto_bukti) : 'N/A') . "\n";
} else {
    echo "- No pengembalian with photos found\n";
}

// Test 3: Check User model photo field
echo "\n3. Testing user photo functionality...\n";

$userWithPhoto = \App\Models\User::whereNotNull('foto')->first();
if ($userWithPhoto) {
    echo "✓ Found user with photo\n";
    echo "  Photo filename: " . $userWithPhoto->foto . "\n";
    $photoPath = storage_path('app/public/uploads/users/' . $userWithPhoto->foto);
    echo "  Photo exists: " . (file_exists($photoPath) ? 'Yes' : 'No') . "\n";
} else {
    echo "- No users with photos found\n";
}

echo "\n=== Test Complete ===\n";
echo "If you see any ✗ markers, those issues need to be fixed.\n";
