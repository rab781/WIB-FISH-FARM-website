<?php

require_once 'vendor/autoload.php';

// Initialize Laravel for testing
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Ulasan;
use App\Models\Pengembalian;
use Illuminate\Support\Facades\Storage;

echo "=== TESTING PHOTO FUNCTIONALITY ===\n\n";

// Test 1: Check User Model for profile photos
echo "1. Testing User Profile Photos:\n";
$users = User::whereNotNull('foto')->take(5)->get();
foreach ($users as $user) {
    $photoPath = 'public/uploads/users/' . $user->foto;
    $exists = Storage::exists($photoPath);
    echo "   User: {$user->name} - Photo: {$user->foto} - Exists: " . ($exists ? 'YES' : 'NO') . "\n";
}

// Test 2: Check Ulasan Model for review photos
echo "\n2. Testing Review Photos:\n";
$reviews = Ulasan::whereNotNull('foto_review')->take(5)->get();
foreach ($reviews as $review) {
    $photos = $review->photos; // Use the accessor method
    $photoUrls = $review->photoUrls; // Use the accessor method
    echo "   Review ID: {$review->id_ulasan} - Has Photos: " . ($review->hasPhotos() ? 'YES' : 'NO') . "\n";
    if ($review->hasPhotos()) {
        echo "     Photos: " . implode(', ', $photos) . "\n";
        echo "     URLs: " . implode(', ', $photoUrls) . "\n";
    }
}

// Test 3: Check Pengembalian Model for refund photos
echo "\n3. Testing Pengembalian Photos:\n";
$pengembalians = Pengembalian::whereNotNull('foto_bukti')->take(5)->get();
foreach ($pengembalians as $pengembalian) {
    $photos = $pengembalian->foto_bukti;
    echo "   Pengembalian ID: {$pengembalian->id_pengembalian} - Photos: ";
    if (is_array($photos)) {
        echo count($photos) . " files\n";
        foreach ($photos as $photo) {
            $photoPath = 'public/pengembalian/' . $photo;
            $exists = Storage::exists($photoPath);
            echo "     - {$photo} - Exists: " . ($exists ? 'YES' : 'NO') . "\n";
        }
    } else {
        echo "Not array format\n";
    }
}

// Test 4: Check storage directories
echo "\n4. Testing Storage Directories:\n";
$directories = [
    'uploads/users',
    'pengembalian',
    'reviews',
    'keluhan'
];

foreach ($directories as $dir) {
    $exists = Storage::disk('public')->exists($dir);
    echo "   Directory: {$dir} - Exists: " . ($exists ? 'YES' : 'NO');
    if ($exists) {
        $files = Storage::disk('public')->files($dir);
        echo " - Files: " . count($files);
    }
    echo "\n";
}

// Test 5: Check symlink
echo "\n5. Testing Storage Symlink:\n";
$symlinkPath = public_path('storage');
$symlinkExists = file_exists($symlinkPath);
echo "   Symlink exists: " . ($symlinkExists ? 'YES' : 'NO') . "\n";
if ($symlinkExists) {
    $realPath = realpath($symlinkPath);
    echo "   Target: {$realPath}\n";
}

// Test 6: Test file upload simulation
echo "\n6. Testing File Upload Permissions:\n";
$testFile = 'test_upload.txt';
$testContent = 'This is a test file for upload permissions';

try {
    Storage::disk('public')->put('uploads/users/' . $testFile, $testContent);
    echo "   Upload test: SUCCESS\n";

    $content = Storage::disk('public')->get('uploads/users/' . $testFile);
    echo "   Read test: " . ($content === $testContent ? 'SUCCESS' : 'FAILED') . "\n";

    Storage::disk('public')->delete('uploads/users/' . $testFile);
    echo "   Delete test: SUCCESS\n";
} catch (Exception $e) {
    echo "   Upload test: FAILED - " . $e->getMessage() . "\n";
}

echo "\n=== TEST COMPLETED ===\n";
