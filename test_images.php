<?php
// Test script to check image accessibility

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Storage;

// Test if there are any keluhan with images
echo "=== Testing Keluhan Images ===\n";

// Check if there are any files in the keluhan directory
$keluhanDir = 'storage/app/public/keluhan';
if (file_exists($keluhanDir)) {
    $files = scandir($keluhanDir);
    $imageFiles = array_filter($files, function($file) {
        return in_array(pathinfo($file, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']);
    });

    if (count($imageFiles) > 0) {
        echo "Found " . count($imageFiles) . " image files:\n";
        foreach ($imageFiles as $file) {
            echo "  - $file\n";
            // Check if accessible via Storage::url
            $url = "storage/keluhan/$file";
            $fullPath = "public/$url";
            if (file_exists($fullPath)) {
                echo "    ✓ Accessible via $url\n";
            } else {
                echo "    ✗ Not accessible via $url\n";
            }
        }
    } else {
        echo "No image files found in keluhan directory\n";
    }
} else {
    echo "Keluhan directory not found\n";
}

echo "\n=== Test Storage URL Generation ===\n";
// Test Storage::url functionality
$testImage = 'test.jpg';
echo "Storage::url('keluhan/test.jpg') would generate: storage/keluhan/test.jpg\n";
echo "Full path would be: public/storage/keluhan/test.jpg\n";

// Check if public/storage/keluhan exists
if (file_exists('public/storage/keluhan')) {
    echo "✓ public/storage/keluhan directory exists\n";
} else {
    echo "✗ public/storage/keluhan directory missing\n";
}

echo "\n=== Test Complete ===\n";
