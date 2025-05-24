<?php

namespace App\Http\Controllers;

use App\Models\Alamat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RajaOngkirController extends Controller
{
    // method untuk mendapatkan data alamat
    public function getAlamat(Request $request)
    {
        Log::info('getAlamat called with search term: ' . $request->search);

        try {
            $response = Http::withHeaders([
                'key' => env('RAJA_ONGKIR_API_KEY'),
            ])->get('https://rajaongkir.komerce.id/api/v1/destination/domestic-destination', [
                'search' => $request->search,
                'limit' => 10,
                'offset' => 0,
            ]);

            Log::info('API Response status: ' . $response->status());
            Log::info('API Response sample: ' . substr($response->body(), 0, 200) . '...');

            // Check if response is successful
            if ($response->successful()) {
                $responseData = $response->json();
                Log::info('API response structure: ' . json_encode(array_keys($responseData)));
                $results = [];

                // Process data from RajaOngkir Komerce API format
                if (isset($responseData['data']) && is_array($responseData['data'])) {
                    // Log first item structure to help with debugging
                    if (!empty($responseData['data'])) {
                        Log::info('Sample item structure: ' . json_encode($responseData['data'][0]));
                    }

                    foreach ($responseData['data'] as $item) {
                        $cityId = $item['id'];

                        // Map API response fields to our expected structure
                        $provinsi = $item['province_name'] ?? $item['province'] ?? '';
                        $kabupaten = $item['city_name'] ?? $item['city'] ?? '';
                        $kecamatan = $item['subdistrict_name'] ?? $item['district_name'] ?? $item['subdistrict'] ?? '';
                        $tipe = $item['type'] ?? '';
                        $kodePos = $item['zip_code'] ?? $item['postal_code'] ?? '';

                        // Save the address to database if not exists
                        if (!Alamat::where('id', $cityId)->exists()) {
                            Alamat::create([
                                'id' => $cityId,
                                'provinsi' => $provinsi,
                                'kabupaten' => $kabupaten,
                                'kecamatan' => $kecamatan,
                                'tipe' => $tipe,
                                'kode_pos' => $kodePos,
                            ]);
                        }

                        $results[] = [
                            'id' => $cityId,
                            'province' => $provinsi,
                            'city' => $kabupaten,
                            'subdistrict' => $kecamatan,
                            'type' => $tipe,
                            'postal_code' => $kodePos,
                        ];
                    }
                }

                return response()->json([
                    'success' => true,
                    'data' => $results
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve data from RajaOngkir API: ' . $response->status(),
                'data' => []
            ], 500);

        } catch (\Exception $e) {
            Log::error('Exception in getAlamat: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    // method cek ongkir
    public function cekOngkir(Request $request)
    {
        // Validate request
        $request->validate([
            'courier' => 'required|string',
            'origin' => 'required',
            'destination' => 'required',
            'weight' => 'required|integer|min:1000', // minimum 1kg in grams
        ]);

        // Add CORS headers
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST");
        header("Access-Control-Allow-Headers: Content-Type, X-Requested-With");

        Log::info('Calculating shipping cost with params:', $request->all());

        try {
            // Call Komerce RajaOngkir API
            $response = Http::withHeaders([
                'key' => env('RAJA_ONGKIR_API_KEY'),
            ])->asForm()->post('https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost', [
                'origin' => $request->origin,
                'destination' => $request->destination,
                'weight' => $request->weight,
                'courier' => $request->courier
            ]);

            $responseData = $response->json();
            Log::info('RajaOngkir API Response:', $responseData);

            // Check if we got a successful response
            if ($response->successful() && isset($responseData['data'])) {
                // Initialize shipping options array
                $shippingOptions = [];

                // Process each courier's cost options
                foreach ($responseData['data'] as $courier) {
                    if (isset($courier['costs']) && is_array($courier['costs'])) {
                        foreach ($courier['costs'] as $cost) {
                            $shippingOptions[] = [
                                'courier' => $courier['code'],
                                'courier_name' => $courier['name'],
                                'service' => $cost['service'],
                                'cost' => $cost['cost'][0]['value'] ?? 0,
                                'etd' => $cost['cost'][0]['etd'] ?? '-',
                                'description' => $cost['description']
                            ];
                        }
                    }
                }

                // If no options found, handle appropriately
                if (empty($shippingOptions)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No shipping options available for the given parameters',
                    ], 404);
                }

                // Sort options by cost and get the default option
                usort($shippingOptions, function($a, $b) {
                    return $a['cost'] - $b['cost'];
                });

                return response()->json([
                    'success' => true,
                    'data' => [
                        'shipping_options' => $shippingOptions,
                        'recommended' => $shippingOptions[0] // Cheapest option as default
                    ]
                ]);
            }

            // If API call was successful but data format is unexpected
            return response()->json([
                'success' => false,
                'message' => 'Invalid response format from shipping API',
                'data' => $responseData
            ], 500);

        } catch (\Exception $e) {
            Log::error('Error in cekOngkir: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to calculate shipping cost: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search for locations from RajaOngkir API
     * Used for the address search functionality in the frontendError: Unexpected token '<', "<!DOCTYPE "... is not valid JSON
     */    public function testKomerce()
    {
        try {
            $apiKey = env('RAJA_ONGKIR_API_KEY');
            Log::info('Using API key: ' . substr($apiKey, 0, 5) . '...');

            // Get full API response for debugging
            $response = Http::withHeaders([
                'key' => $apiKey,
            ])->get('https://rajaongkir.komerce.id/api/v1/destination/domestic-destination', [
                'search' => 'jakarta',
                'limit' => 5,
                'offset' => 0,
            ]);

            $statusCode = $response->status();
            $responseBody = $response->body();

            Log::info("Test API Response Status: {$statusCode}");
            Log::info("Test API Response Body: " . $responseBody);

            return response()->json([
                'success' => $response->successful(),
                'status_code' => $statusCode,
                'raw_response' => $responseBody,
                'parsed_response' => $response->json()
            ]);
        } catch (\Exception $e) {
            Log::error('Exception in testKomerce: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function searchLocation(Request $request)
    {
        $term = $request->term;

        // Add CORS headers to make sure API works from all origins
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST");
        header("Access-Control-Allow-Headers: Content-Type");

        Log::info('Searching for alamat with term: ' . $term);

        if (strlen($term) < 3) {
            return response()->json([
                'success' => false,
                'message' => 'Search term must be at least 3 characters',
                'data' => []
            ]);
        }

        try {
            // Try to use the Komerce API key first, if that fails, fall back to the standard key
            $apiKey = env('RAJA_ONGKIR_API_KEY_KOMERCE', env('RAJA_ONGKIR_API_KEY'));
            Log::info('Using RajaOngkir API key: ' . substr($apiKey, 0, 5) . '...');

            // Sesuai dokumentasi RajaOngkir API dari komerceapi.readme.io
            $url = 'https://rajaongkir.komerce.id/api/v1/destination/domestic-destination';
            Log::info('Fetching from URL: ' . $url . ' with search term: ' . $term);

            try {
                $response = Http::withHeaders([
                    'key' => $apiKey,
                ])->timeout(30)->get($url, [
                    'search' => $term,
                    'limit' => 10,
                    'offset' => 0
                ]);

                Log::info('API Response status: ' . $response->status());

                // Check if the response is valid JSON before trying to parse it
                $responseBody = $response->body();
                Log::info('API Response body: ' . substr($responseBody, 0, 500) . '...');

                // Make sure we can parse the JSON
                if ($response->successful() && $this->isValidJson($responseBody)) {
                    $responseData = $response->json();
                    Log::info('Full API response structure: ' . json_encode($responseData));
                } else {
                    Log::error('Invalid JSON response or failed request');
                    $responseData = ['success' => false, 'data' => []];
                }
            } catch (\Exception $e) {
                Log::error('Exception making API request: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Error connecting to RajaOngkir API: ' . $e->getMessage(),
                    'data' => []
                ], 500);
            }

            if (isset($responseData) && $response->successful()) {
                Log::info('Parsed response data structure: ' . json_encode(array_keys($responseData)));

                $results = [];

                // Process data from RajaOngkir Komerce API format
                if (isset($responseData['data']) && is_array($responseData['data'])) {
                    foreach ($responseData['data'] as $item) {
                        $cityId = $item['id'] ?? 0;

                        // Map API response fields to our expected structure - handle variability in API response
                        $provinsi = $item['province_name'] ?? $item['province'] ?? '';
                        $kabupaten = $item['city_name'] ?? $item['city'] ?? '';
                        $kecamatan = $item['subdistrict_name'] ?? $item['district_name'] ?? $item['subdistrict'] ?? '';
                        $tipe = $item['type'] ?? ''; // Type is not consistently provided in API response
                        $kodePos = $item['zip_code'] ?? $item['postal_code'] ?? '';

                        // Only process valid entries
                        if ($cityId > 0) {
                            // Save the address to database if not exists
                            if (!Alamat::where('id', $cityId)->exists()) {
                                Alamat::create([
                                    'id' => $cityId,
                                    'provinsi' => $provinsi,
                                    'kabupaten' => $kabupaten,
                                    'kecamatan' => $kecamatan,
                                    'tipe' => $tipe,
                                    'kode_pos' => $kodePos,
                                ]);
                            }

                            $results[] = [
                                'id' => $cityId,
                                'province' => $provinsi,
                                'city' => $kabupaten,
                                'subdistrict' => $kecamatan,
                                'type' => $tipe,
                                'postal_code' => $kodePos,
                            ];

                            // Log each processed result for debugging
                            Log::info('Processed address item: ' . json_encode([
                                'id' => $cityId,
                                'province' => $provinsi,
                                'city' => $kabupaten,
                                'subdistrict' => $kecamatan
                            ]));
                        }
                    }
                }

                Log::info('Found ' . count($results) . ' matching cities');
                return response()->json([
                    'success' => true,
                    'data' => $results
                ]);
            }

            Log::error('RajaOngkir API request failed: ' . $response->status() . ' - ' . $response->body());

            // API failed - check if the error is due to an invalid API key
            if (strpos($response->body(), 'Invalid Api key') !== false) {
                Log::warning('API key appears to be invalid. Using local database as fallback.');
            }

            // Try to get data from local database as fallback
            $localResults = $this->getLocalAlamatData($term);

            if (!empty($localResults)) {
                Log::info('Using local database as fallback for: ' . $term);
                return response()->json([
                    'success' => true,
                    'message' => 'Data retrieved from local database',
                    'data' => $localResults
                ]);
            }

            // If we have a test environment or demo mode, we could return mock data
            if (env('APP_ENV') !== 'production' && empty($localResults)) {
                Log::info('Using mock data for term: ' . $term);
                $mockData = $this->getMockAlamatData($term);
                if (!empty($mockData)) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Demo data retrieved',
                        'data' => $mockData
                    ]);
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve data from RajaOngkir API: ' . $response->status(),
                'data' => []
            ], 500);

        } catch (\Exception $e) {
            Log::error('Exception in searchLocation: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            // Check if we can use local data as fallback in case of exception
            $localResults = $this->getLocalAlamatData($term);
            if (!empty($localResults)) {
                Log::info('Using local database after exception for: ' . $term);
                return response()->json([
                    'success' => true,
                    'message' => 'Data retrieved from local database',
                    'data' => $localResults
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Test the RajaOngkir standard API to see if it works with our API key
     */
    public function testStandard()
    {
        try {
            $apiKey = env('RAJA_ONGKIR_API_KEY');
            Log::info('Using API key for standard API test: ' . substr($apiKey, 0, 5) . '...');

            // Get full API response for debugging - try standard API endpoint
            $url = 'https://rajaongkir.komerce.id/api/v1/destination/domestic-destination';
            $response = Http::withHeaders([
                'key' => $apiKey,
            ])->get($url);

            $statusCode = $response->status();
            $responseBody = $response->body();

            Log::info("Standard API Test Response Status: {$statusCode}");
            Log::info("Standard API Test Response Body: " . substr($responseBody, 0, 500) . '...');

            return response()->json([
                'success' => $response->successful(),
                'status_code' => $statusCode,
                'raw_response' => $responseBody,
                'parsed_response' => $response->json()
            ]);
        } catch (\Exception $e) {
            Log::error('Exception in testStandard: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test the RajaOngkir API to check if all important endpoints work
     */
    public function testApiStatus()
    {
        try {
            $apiKey = env('RAJA_ONGKIR_API_KEY');
            $results = [];

            // Test 1: Domestic Destination Search
            $domesticDestResponse = Http::withHeaders([
                'key' => $apiKey,
            ])->get('https://rajaongkir.komerce.id/api/v1/destination/domestic-destination', [
                'search' => 'jakarta',
                'limit' => 2,
                'offset' => 0,
            ]);

            $results['domestic_destination'] = [
                'success' => $domesticDestResponse->successful(),
                'status_code' => $domesticDestResponse->status(),
                'response_sample' => $domesticDestResponse->successful() ?
                    array_slice($domesticDestResponse->json(), 0, 2) :
                    $domesticDestResponse->body()
            ];

            // Test 2: International Destination Search
            $intDestResponse = Http::withHeaders([
                'key' => $apiKey,
            ])->get('https://rajaongkir.komerce.id/api/v1/destination/international-destination', [
                'search' => 'singapore',
                'limit' => 2,
                'offset' => 0,
            ]);

            $results['international_destination'] = [
                'success' => $intDestResponse->successful(),
                'status_code' => $intDestResponse->status(),
                'response_sample' => $intDestResponse->successful() ?
                    array_slice($intDestResponse->json(), 0, 2) :
                    $intDestResponse->body()
            ];

            // Test 3: Domestic Cost Calculation
            $domesticCostResponse = Http::withHeaders([
                'key' => $apiKey,
            ])->asForm()->post('https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost', [
                'origin' => '152', // Jakarta example ID
                'destination' => '153', // Another city example ID
                'weight' => 1000,
                'courier' => 'jne',
            ]);

            $results['domestic_cost'] = [
                'success' => $domesticCostResponse->successful(),
                'status_code' => $domesticCostResponse->status(),
                'response_sample' => $domesticCostResponse->successful() ?
                    array_slice($domesticCostResponse->json(), 0, 2) :
                    $domesticCostResponse->body()
            ];

            // Test 4: International Cost Calculation
            $intCostResponse = Http::withHeaders([
                'key' => $apiKey,
            ])->asForm()->post('https://rajaongkir.komerce.id/api/v1/calculate/international-cost', [
                'origin' => '152', // Jakarta example ID
                'destination' => '1', // Singapore example ID
                'weight' => 1000,
                'courier' => 'pos',
            ]);

            $results['international_cost'] = [
                'success' => $intCostResponse->successful(),
                'status_code' => $intCostResponse->status(),
                'response_sample' => $intCostResponse->successful() ?
                    array_slice($intCostResponse->json(), 0, 2) :
                    $intCostResponse->body()
            ];

            // Test 5: Tracking if available
            $trackingResponse = Http::withHeaders([
                'key' => $apiKey,
            ])->asForm()->post('https://rajaongkir.komerce.id/api/v1/track/waybill', [
                'courier' => 'jne',
                'awb' => '0637132400441624', // Example waybill number
            ]);

            $results['tracking'] = [
                'success' => $trackingResponse->successful(),
                'status_code' => $trackingResponse->status(),
                'response_sample' => $trackingResponse->successful() ?
                    array_slice($trackingResponse->json(), 0, 2) :
                    substr($trackingResponse->body(), 0, 300)
            ];

            // Summary of all tests
            $overallSuccess =
                $results['domestic_destination']['success'] ||
                $results['international_destination']['success'] ||
                $results['domestic_cost']['success'] ||
                $results['international_cost']['success'];

            return response()->json([
                'success' => $overallSuccess,
                'api_key_available' => !empty($apiKey),
                'test_results' => $results,
                'message' => $overallSuccess ? 'API connection established successfully for at least one endpoint.' : 'Failed to connect to any API endpoints. Please check your API key and network connection.'
            ]);

        } catch (\Exception $e) {
            Log::error('Exception in testApiStatus: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    /**
     * Metode untuk mengecek status tracking paket menggunakan RajaOngkir API
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cekTrackingStatus(Request $request)
    {
        // Validasi request
        $request->validate([
            'courier' => 'required|string',
            'waybill' => 'required|string',
        ]);

        Log::info('Checking tracking status with params: ' . json_encode($request->all()));

        try {
            $response = Http::withHeaders([
                'key' => env('RAJA_ONGKIR_API_KEY'),
            ])->asForm()->post('https://rajaongkir.komerce.id/api/v1/track/waybill', [
                'courier' => $request->courier,
                'awb' => $request->waybill, // menggunakan parameter waybill dari request tapi dikirim sebagai awb
            ]);

            Log::info('API Response status: ' . $response->status());
            Log::info('API Response sample: ' . substr($response->body(), 0, 200) . '...');

            if ($response->successful()) {
                $responseData = $response->json();

                if (isset($responseData['data']) && is_array($responseData['data'])) {
                    return response()->json([
                        'success' => true,
                        'data' => $responseData['data']
                    ]);
                }

                Log::warning('Unexpected response structure: ' . json_encode(array_keys($responseData)));
                return response()->json([
                    'success' => false,
                    'message' => 'Unexpected response structure from RajaOngkir API',
                    'data' => []
                ], 500);
            }

            Log::error('Failed to get tracking status: ' . $response->status() . ' - ' . $response->body());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve tracking status from RajaOngkir API: ' . $response->status(),
                'data' => []
            ], 500);
        } catch (\Exception $e) {
            Log::error('Exception in cekTrackingStatus: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Helper function to check if string is valid JSON
     *
     * @param string $string The string to check
     * @return bool
     */
    private function isValidJson($string) {
        if (!is_string($string)) {
            return false;
        }

        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * Get address data from local database as fallback when API fails
     *
     * @param string $term Search term
     * @return array
     */
    private function getLocalAlamatData($term)
    {
        try {
            // Search in the alamat table
            $alamatData = Alamat::where('provinsi', 'like', "%{$term}%")
                ->orWhere('kabupaten', 'like', "%{$term}%")
                ->orWhere('kecamatan', 'like', "%{$term}%")
                ->take(10)
                ->get();

            if ($alamatData->isEmpty()) {
                return [];
            }

            $results = [];
            foreach ($alamatData as $alamat) {
                $results[] = [
                    'id' => $alamat->id,
                    'province' => $alamat->provinsi,
                    'city' => $alamat->kabupaten,
                    'subdistrict' => $alamat->kecamatan,
                    'type' => $alamat->tipe,
                    'postal_code' => $alamat->kode_pos,
                ];
            }

            return $results;
        } catch (\Exception $e) {
            Log::error('Error getting local alamat data: ' . $e->getMessage());
            return [];
        }
    }
}
