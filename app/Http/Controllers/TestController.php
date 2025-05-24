<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alamat;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class TestController extends Controller
{
    /**
     * Test endpoint untuk mencoba RajaOngkir API secara langsung dari browser
     */
    public function testRajaOngkirSearch(Request $request)
    {
        $term = $request->query('term', 'jakarta'); // Default search term
        $apiKey = env('RAJA_ONGKIR_API_KEY');

        try {
            // Langsung gunakan API key di sini untuk testing
            $response = Http::withHeaders([
                'key' => $apiKey,
            ])->get('https://rajaongkir.komerce.id/api/v1/destination/domestic-destination', [
                'search' => $term,
                'limit' => 10,
                'offset' => 0
            ]);

            // Log response untuk debugging
            Log::info("Test search for '$term' - Status: " . $response->status());
            Log::info("Response body: " . substr($response->body(), 0, 500));

            // Proses hasil respons untuk tampilan
            $addressData = [];
            if ($response->successful()) {
                $responseJson = $response->json();

                if (isset($responseJson['data']) && is_array($responseJson['data'])) {
                    foreach ($responseJson['data'] as $item) {
                        $addressData[] = [
                            'id' => $item['id'] ?? 'N/A',
                            'label' => $item['label'] ?? 'N/A',
                            'province' => $item['province_name'] ?? 'N/A',
                            'city' => $item['city_name'] ?? 'N/A',
                            'district' => $item['district_name'] ?? 'N/A',
                            'subdistrict' => $item['subdistrict_name'] ?? 'N/A',
                            'postal_code' => $item['zip_code'] ?? 'N/A'
                        ];
                    }
                }
            }

            // Tampilkan halaman debug dengan hasil
            return view('test.raja-ongkir-search', [
                'term' => $term,
                'status' => $response->status(),
                'success' => $response->successful(),
                'results' => $addressData,
                'raw_response' => substr($response->body(), 0, 2000), // batasi untuk tampilan
                'api_key_masked' => substr($apiKey, 0, 5) . '...' . substr($apiKey, -3)
            ]);

        } catch (\Exception $e) {
            Log::error('Error in testRajaOngkirSearch: ' . $e->getMessage());

            return view('test.raja-ongkir-search', [
                'term' => $term,
                'error' => $e->getMessage(),
                'api_key_masked' => !empty($apiKey) ? (substr($apiKey, 0, 5) . '...' . substr($apiKey, -3)) : 'Not configured'
            ]);
        }
    }

    /**
     * Test direct API response and display raw results
     */
    public function testApiResponse(Request $request)
    {
        $term = $request->query('term');

        // If no search term provided, just show the form
        if (empty($term)) {
            return view('test.api-response');
        }

        try {
            $apiKey = env('RAJA_ONGKIR_API_KEY');
            $apiUrl = '/api/alamat/search?term=' . urlencode($term);

            // Make the same request the JS would make
            $response = Http::get(url($apiUrl));
            $status = $response->status();
            $rawResponse = $response->body();

            // Try to parse the JSON
            $parsedData = null;
            if ($response->successful()) {
                $parsedData = $response->json();
            }

            return view('test.api-response', [
                'term' => $term,
                'apiUrl' => $apiUrl,
                'status' => $status,
                'response' => $rawResponse,
                'parsedData' => $parsedData
            ]);

        } catch (\Exception $e) {
            return view('test.api-response', [
                'term' => $term,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Show an integrated debugging page for address search
     */
    public function debugAddressSearch()
    {
        return view('test.debug');
    }

    /**
     * Show enhanced address search test page
     */
    public function enhancedSearch()
    {
        return view('test.enhanced-search');
    }
}
