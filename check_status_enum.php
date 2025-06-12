<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    $result = DB::select('SHOW COLUMNS FROM pesanan WHERE Field = "status_pesanan"');

    if (!empty($result)) {
        echo "Current status_pesanan column definition:\n";
        echo "Field: " . $result[0]->Field . "\n";
        echo "Type: " . $result[0]->Type . "\n";
        echo "Null: " . $result[0]->Null . "\n";
        echo "Key: " . $result[0]->Key . "\n";
        echo "Default: " . $result[0]->Default . "\n";
        echo "Extra: " . $result[0]->Extra . "\n";
    } else {
        echo "Column status_pesanan not found\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
