<?php
// Test script to verify functionality issues

require_once 'vendor/autoload.php';

// Test 1: Check if storage directories exist
echo "=== Testing Storage Functionality ===\n";

$storagePaths = [
    'storage/app/public/reviews',
    'storage/app/public/keluhan',
    'storage/app/public/pengembalian',
    'public/storage'
];

foreach ($storagePaths as $path) {
    if (file_exists($path)) {
        echo "✓ $path exists\n";
    } else {
        echo "✗ $path missing\n";
    }
}

// Test 2: Check if symlink is working
echo "\n=== Testing Storage Symlink ===\n";
if (is_link('public/storage')) {
    echo "✓ Storage symlink exists\n";
    echo "  Target: " . readlink('public/storage') . "\n";
} else {
    echo "✗ Storage symlink missing\n";
}

// Test 3: Check for potential permission issues
echo "\n=== Testing Directory Permissions ===\n";
$dirs = ['storage/app/public', 'public/storage'];
foreach ($dirs as $dir) {
    if (file_exists($dir)) {
        $perms = substr(sprintf('%o', fileperms($dir)), -4);
        echo "  $dir: $perms\n";
    }
}

echo "\n=== Test Complete ===\n";
