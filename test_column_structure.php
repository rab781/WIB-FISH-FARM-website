<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Checking pesanan table structure...\n";

try {
    $columns = DB::select('DESCRIBE pesanan');
    foreach($columns as $column) {
        if($column->Field === 'status_pesanan') {
            echo "Column: {$column->Field}\n";
            echo "Type: {$column->Type}\n";
            echo "Null: {$column->Null}\n";
            echo "Default: {$column->Default}\n";
            echo "Extra: {$column->Extra}\n";
            break;
        }
    }

    echo "\nTesting direct SQL update...\n";
    $result = DB::statement("UPDATE pesanan SET status_pesanan = 'Dikirim' WHERE id_pesanan = 1");
    echo "Direct SQL update result: " . ($result ? 'SUCCESS' : 'FAILED') . "\n";

} catch(Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
