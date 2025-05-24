<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    // ... existing methods ...
    
    /**
     * Tampilkan halaman status API untuk admin
     */
    public function debugApiStatus()
    {
        $apiKey = env('RAJA_ONGKIR_API_KEY', 'not-configured');
        $apiKeyKomerce = env('RAJA_ONGKIR_API_KEY_KOMERCE', 'not-configured');
        $appDebug = env('APP_DEBUG', false);
        
        // Pengujian koneksi API secara sederhana
        $testResult = $this->doSimpleApiTest();
        
        return view('admin.debug.api_status', [
            'apiKey' => substr($apiKey, 0, 5) . '...' . substr($apiKey, -3),
            'apiKeyKomerce' => substr($apiKeyKomerce, 0, 5) . '...' . substr($apiKeyKomerce, -3),
            'appDebug' => $appDebug ? 'True' : 'False',
            'testResult' => $testResult,
            'serverInfo' => [
                'php' => PHP_VERSION,
                'server' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
                'os' => PHP_OS,
            ]
        ]);
    }
    
    /**
     * Melakukan pengujian API sederhana
     */
    private function doSimpleApiTest()
    {
        try {
            $apiKey = env('RAJA_ONGKIR_API_KEY_KOMERCE', env('RAJA_ONGKIR_API_KEY'));
            $url = 'https://rajaongkir.komerce.id/api/v1/destination/domestic-destination';
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url . '?search=jakarta&limit=1&offset=0');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'key: ' . $apiKey
            ]);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            
            $response = curl_exec($ch);
            $error = curl_error($ch);
            $info = curl_getinfo($ch);
            curl_close($ch);
            
            if ($error) {
                return [
                    'success' => false,
                    'message' => 'CURL Error: ' . $error,
                    'http_code' => $info['http_code'] ?? 'Unknown'
                ];
            }
            
            return [
                'success' => true,
                'http_code' => $info['http_code'] ?? 'Unknown', 
                'response_time' => $info['total_time'] ?? 0,
                'size' => $info['size_download'] ?? 0,
                'sample' => substr($response, 0, 100) . '...'
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Exception: ' . $e->getMessage()
            ];
        }
    }
}
