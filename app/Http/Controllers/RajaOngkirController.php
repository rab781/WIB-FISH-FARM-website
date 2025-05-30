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

    // method cek ongkir - optimized for TIKI courier only
    public function cekOngkir(Request $request)
    {
        // Validate request
        $request->validate([
            'origin' => 'required',
            'destination' => 'required',
            'weight' => 'required|integer|min:1000', // minimum 1kg in grams
        ]);

        // Add CORS headers
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST");
        header("Access-Control-Allow-Headers: Content-Type, X-Requested-With");

        // Force TIKI as the courier for fish shipping
        $courier = 'tiki';

        Log::info('Calculating TIKI shipping cost with params:', [
            'origin' => $request->origin,
            'destination' => $request->destination,
            'weight' => $request->weight,
            'courier' => $courier
        ]);

        try {
            // Call Komerce RajaOngkir API with TIKI courier
            $response = Http::withHeaders([
                'key' => env('RAJA_ONGKIR_API_KEY'),
            ])->asForm()->post('https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost', [
                'origin' => $request->origin,
                'destination' => $request->destination,
                'weight' => $request->weight,
                'courier' => $courier
            ]);

            $responseData = $response->json();
            Log::info('RajaOngkir API Response:', $responseData);

            // Check if we got a successful response
            if ($response->successful() && isset($responseData['data']) && !empty($responseData['data'])) {
                // Initialize shipping options array
                $shippingOptions = [];

                // Process TIKI courier options - based on the successful test response format
                foreach ($responseData['data'] as $courierData) {
                    if ($courierData['code'] === 'tiki') {
                        $shippingOptions[] = [
                            'courier' => $courierData['code'],
                            'courier_name' => $courierData['name'],
                            'service' => $courierData['service'],
                            'service_description' => $courierData['description'],
                            'cost' => $courierData['cost'],
                            'etd' => $courierData['etd'],
                            'formatted_cost' => 'Rp ' . number_format($courierData['cost'], 0, ',', '.'),
                            'etd_text' => $courierData['etd'] . ' hari'
                        ];
                    }
                }

                // If no TIKI options found, handle appropriately
                if (empty($shippingOptions)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'TIKI shipping service is not available for this route',
                        'error_type' => 'NO_TIKI_SERVICE'
                    ], 404);
                }

                // Sort options by cost (cheapest first)
                usort($shippingOptions, function($a, $b) {
                    return $a['cost'] - $b['cost'];
                });

                // Filter out expensive motorcycle/trucking services for fish shipping
                $fishShippingOptions = array_filter($shippingOptions, function($option) {
                    $service = strtoupper($option['service']);
                    // Exclude motorcycle (T15, T25, T60) and trucking (TRC) services
                    return !in_array($service, ['T15', 'T25', 'T60', 'TRC']);
                });

                // Re-index array after filtering
                $fishShippingOptions = array_values($fishShippingOptions);

                return response()->json([
                    'success' => true,
                    'data' => [
                        'shipping_options' => $fishShippingOptions,
                        'recommended' => $fishShippingOptions[0] ?? null, // Cheapest suitable option
                        'total_options' => count($fishShippingOptions),
                        'courier_info' => [
                            'name' => 'TIKI (Citra Van Titipan Kilat)',
                            'code' => 'tiki',
                            'specialization' => 'Specialized for live fish delivery'
                        ]
                    ]
                ]);
            }

            // If API call was successful but no data
            return response()->json([
                'success' => false,
                'message' => 'No shipping cost data available from TIKI',
                'error_type' => 'NO_DATA',
                'debug_response' => $responseData
            ], 500);

        } catch (\Exception $e) {
            Log::error('Error in cekOngkir: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to calculate TIKI shipping cost: ' . $e->getMessage(),
                'error_type' => 'EXCEPTION'
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
     * Public address search endpoint for frontend testing
     * No authentication required
     */
    public function publicSearchAlamat(Request $request)
    {
        // Add CORS headers
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST");
        header("Access-Control-Allow-Headers: Content-Type, X-Requested-With");

        $searchTerm = $request->input('search', '');

        Log::info('Public address search called with term: ' . $searchTerm);

        if (strlen($searchTerm) < 2) {
            return response()->json([
                'success' => false,
                'message' => 'Search term must be at least 2 characters',
                'data' => []
            ]);
        }

        try {
            $response = Http::withHeaders([
                'key' => env('RAJA_ONGKIR_API_KEY'),
            ])->get('https://rajaongkir.komerce.id/api/v1/destination/domestic-destination', [
                'search' => $searchTerm,
                'limit' => 10,
                'offset' => 0,
            ]);

            Log::info('Public search API Response status: ' . $response->status());

            if ($response->successful()) {
                $responseData = $response->json();
                $results = [];

                if (isset($responseData['data']) && is_array($responseData['data'])) {
                    foreach ($responseData['data'] as $item) {
                        $cityId = $item['id'];
                        $provinsi = $item['province_name'] ?? $item['province'] ?? '';
                        $kabupaten = $item['city_name'] ?? $item['city'] ?? '';
                        $kecamatan = $item['subdistrict_name'] ?? $item['district_name'] ?? $item['subdistrict'] ?? '';
                        $kodePos = $item['zip_code'] ?? $item['postal_code'] ?? '';

                        $fullAddress = trim("{$kabupaten}, {$kecamatan}, {$provinsi} {$kodePos}");

                        $results[] = [
                            'city_id' => $cityId,
                            'provinsi' => $provinsi,
                            'kabupaten' => $kabupaten,
                            'kecamatan' => $kecamatan,
                            'kode_pos' => $kodePos,
                            'full_address' => $fullAddress
                        ];
                    }
                }

                return response()->json([
                    'success' => true,
                    'data' => $results,
                    'count' => count($results)
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch address data',
                'data' => []
            ], 500);

        } catch (\Exception $e) {
            Log::error('Error in publicSearchAlamat: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Search failed: ' . $e->getMessage(),
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
     * Get mock address data for testing purposes
     *
     * @param string $term Search term
     * @return array
     */
    private function getMockAlamatData($term)
    {
        // Mock data for testing purposes
        $mockData = [
            [
                'id' => 1,
                'province' => 'Jakarta',
                'city' => 'Jakarta Pusat',
                'subdistrict' => 'Gambir',
                'type' => 'Kota',
                'postal_code' => '10110',
            ],
            [
                'id' => 2,
                'province' => 'Jawa Barat',
                'city' => 'Bandung',
                'subdistrict' => 'Sumur Bandung',
                'type' => 'Kota',
                'postal_code' => '40111',
            ],
            [
                'id' => 3,
                'province' => 'Jawa Timur',
                'city' => 'Surabaya',
                'subdistrict' => 'Genteng',
                'type' => 'Kota',
                'postal_code' => '60275',
            ],
        ];

        // Filter mock data based on search term
        return array_filter($mockData, function($item) use ($term) {
            return stripos($item['province'], $term) !== false ||
                   stripos($item['city'], $term) !== false ||
                   stripos($item['subdistrict'], $term) !== false;
        });
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

    /**
     * Debug method to test exact request differences between cURL and Laravel HTTP
     */
    public function debugHttpRequest()
    {
        try {
            $apiKey = env('RAJA_ONGKIR_API_KEY');
            Log::info('DEBUG: Testing HTTP request differences');
            Log::info('DEBUG: API Key: ' . substr($apiKey, 0, 5) . '...');

            $url = 'https://rajaongkir.komerce.id/api/v1/destination/domestic-destination';
            $params = [
                'search' => 'jakarta',
                'limit' => 2,
                'offset' => 0
            ];

            // Test 1: Basic Laravel HTTP request (current method)
            Log::info('DEBUG: Testing basic Laravel HTTP request');
            $response1 = Http::withHeaders([
                'key' => $apiKey,
            ])->get($url, $params);

            Log::info('DEBUG: Basic request status: ' . $response1->status());
            Log::info('DEBUG: Basic request body sample: ' . substr($response1->body(), 0, 200));

            // Test 2: Laravel HTTP with explicit User-Agent
            Log::info('DEBUG: Testing with explicit User-Agent');
            $response2 = Http::withHeaders([
                'key' => $apiKey,
                'User-Agent' => 'Laravel/11.0 PHP/' . PHP_VERSION,
            ])->get($url, $params);

            Log::info('DEBUG: User-Agent request status: ' . $response2->status());
            Log::info('DEBUG: User-Agent request body sample: ' . substr($response2->body(), 0, 200));

            // Test 3: Laravel HTTP with cURL-like headers
            Log::info('DEBUG: Testing with cURL-like headers');
            $response3 = Http::withHeaders([
                'key' => $apiKey,
                'User-Agent' => 'curl/7.68.0',
                'Accept' => '*/*',
            ])->get($url, $params);

            Log::info('DEBUG: cURL-like request status: ' . $response3->status());
            Log::info('DEBUG: cURL-like request body sample: ' . substr($response3->body(), 0, 200));

            // Test 4: Using different HTTP options
            Log::info('DEBUG: Testing with timeout and verify options');
            $response4 = Http::withHeaders([
                'key' => $apiKey,
            ])->timeout(30)
              ->withOptions([
                  'verify' => false, // Disable SSL verification temporarily
              ])->get($url, $params);

            Log::info('DEBUG: No-verify request status: ' . $response4->status());
            Log::info('DEBUG: No-verify request body sample: ' . substr($response4->body(), 0, 200));

            // Test 5: Raw cURL through Laravel
            Log::info('DEBUG: Testing raw cURL through Laravel');
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => $url . '?' . http_build_query($params),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => [
                    'key: ' . $apiKey,
                ],
                CURLOPT_TIMEOUT => 30,
            ]);

            $curlResponse = curl_exec($curl);
            $curlHttpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $curlError = curl_error($curl);
            curl_close($curl);

            Log::info('DEBUG: Raw cURL status: ' . $curlHttpCode);
            Log::info('DEBUG: Raw cURL error: ' . ($curlError ?: 'none'));
            Log::info('DEBUG: Raw cURL body sample: ' . substr($curlResponse, 0, 200));

            return response()->json([
                'success' => true,
                'tests' => [
                    'basic_laravel' => [
                        'status' => $response1->status(),
                        'success' => $response1->successful(),
                        'body_sample' => substr($response1->body(), 0, 200),
                    ],
                    'with_user_agent' => [
                        'status' => $response2->status(),
                        'success' => $response2->successful(),
                        'body_sample' => substr($response2->body(), 0, 200),
                    ],
                    'curl_like_headers' => [
                        'status' => $response3->status(),
                        'success' => $response3->successful(),
                        'body_sample' => substr($response3->body(), 0, 200),
                    ],
                    'no_ssl_verify' => [
                        'status' => $response4->status(),
                        'success' => $response4->successful(),
                        'body_sample' => substr($response4->body(), 0, 200),
                    ],
                    'raw_curl' => [
                        'status' => $curlHttpCode,
                        'success' => $curlHttpCode == 200,
                        'error' => $curlError,
                        'body_sample' => substr($curlResponse, 0, 200),
                    ],
                ],
                'api_key_used' => substr($apiKey, 0, 5) . '...' . substr($apiKey, -3),
            ]);

        } catch (\Exception $e) {
            Log::error('DEBUG: Exception in debugHttpRequest: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    /**
     * Test domestic shipping cost calculation specifically for TIKI courier
     */
    public function testDomesticCost()
    {
        try {
            $apiKey = env('RAJA_ONGKIR_API_KEY');
            Log::info('Testing domestic cost calculation with TIKI courier');
            Log::info('Using API key: ' . substr($apiKey, 0, 5) . '...');

            // Test with known city IDs (Jakarta to Bandung example)
            $testParams = [
                'origin' => '152', // Jakarta (common ID)
                'destination' => '22', // Bandung (common ID)
                'weight' => 1000, // 1kg in grams
                'courier' => 'tiki'
            ];

            Log::info('Test parameters: ' . json_encode($testParams));

            $response = Http::withHeaders([
                'key' => $apiKey,
            ])->asForm()->post('https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost', $testParams);

            $statusCode = $response->status();
            $responseBody = $response->body();

            Log::info("Domestic cost test status: {$statusCode}");
            Log::info("Domestic cost test response: " . $responseBody);

            if ($response->successful()) {
                $responseData = $response->json();

                // Log the structure to understand the response format
                Log::info('Response data structure: ' . json_encode(array_keys($responseData)));

                if (isset($responseData['data'])) {
                    Log::info('Data array structure: ' . json_encode($responseData['data']));
                }

                return response()->json([
                    'success' => true,
                    'status_code' => $statusCode,
                    'test_parameters' => $testParams,
                    'response_data' => $responseData,
                    'message' => 'TIKI domestic cost calculation test successful'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'status_code' => $statusCode,
                    'test_parameters' => $testParams,
                    'error_response' => $responseBody,
                    'message' => 'TIKI domestic cost calculation test failed'
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Exception in testDomesticCost: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
}
