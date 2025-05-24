<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TestApiController extends Controller
{
    /**
     * Test the RajaOngkir API connection
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function testRajaOngkir()
    {
        try {
            $apiKey = env('RAJA_ONGKIR_API_KEY');

            if (empty($apiKey)) {
                return response()->json([
                    'success' => false,
                    'message' => 'API Key is not set in the .env file',
                    'status' => 'CONFIGURATION_ERROR'
                ], 500);
            }

            // Log full API key for debugging (this should be removed in production)
            Log::debug('API key for debugging: ' . $apiKey);

            // Test the API using Komerce API format
            $url = 'https://rajaongkir.komerce.id/api/v1/destination/domestic-destination';
            $response = Http::withHeaders([
                'key' => $apiKey,
            ])->get($url, [
                'search' => 'jakarta',
                'limit' => 5,
                'offset' => 0,
            ]);

            $statusCode = $response->status();
            $responseBody = $response->body();

            Log::info("Test API Response Status: {$statusCode}");
            Log::info("Test API Response Body: " . substr($responseBody, 0, 500) . '...');

            if ($response->successful()) {
                $data = $response->json();

                return response()->json([
                    'success' => true,
                    'message' => 'API connection successful',
                    'status_code' => $statusCode,
                    'response_structure' => array_keys($data),
                    'data_sample' => isset($data['data']) && count($data['data']) > 0 ? $data['data'][0] : null,
                    'data_count' => isset($data['data']) ? count($data['data']) : 0
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'API connection failed',
                'status_code' => $statusCode,
                'error' => $responseBody,
                'status' => 'API_ERROR'
            ], 500);

        } catch (\Exception $e) {
            Log::error('Exception in testRajaOngkir: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Exception occurred while testing API',
                'error' => $e->getMessage(),
                'status' => 'EXCEPTION'
            ], 500);
        }
    }

    /**
     * Test RajaOngkir API specifically with the 'bon' search term
     */
    public function testRajaOngkirWithBon()
    {
        try {
            $apiKey = env('RAJA_ONGKIR_API_KEY');
            Log::info('Testing RajaOngkir API with term "bon" and key: ' . substr($apiKey, 0, 5) . '...');
            
            // Testing with the exact search term "bon"
            $searchTerm = 'bon';
            
            $url = 'https://rajaongkir.komerce.id/api/v1/destination/domestic-destination';
            $response = Http::withHeaders([
                'key' => $apiKey,
            ])->get($url, [
                'search' => $searchTerm,
                'limit' => 10,
                'offset' => 0
            ]);
            
            Log::info('API Response status: ' . $response->status());
            Log::info('API Response body: ' . substr($response->body(), 0, 1000) . '...');
            
            return response()->json([
                'success' => $response->successful(),
                'status_code' => $response->status(),
                'search_term' => $searchTerm,
                'raw_body_sample' => substr($response->body(), 0, 1000),
                'parsed_data' => $response->json()
            ]);
        } catch (\Exception $e) {
            Log::error('Exception in testRajaOngkirWithBon: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
}
