<?php

namespace App\Http\Controllers\Admin;

use App\Models\Pesanan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DiagnosticController extends Controller
{
    /**
     * Check payment proof image paths
     */
    public function checkPaymentProofPaths(Request $request, string $id = null)
    {
        // Check if user is admin - use different approaches since model might vary
        if (!$request->user() ||
            (!method_exists($request->user(), 'isAdmin') || !$request->user()->isAdmin()) &&
            (!isset($request->user()->role) || $request->user()->role !== 'admin')
        ) {
            return response()->json([
                'error' => 'Unauthorized',
                'message' => 'This endpoint is only accessible to administrators'
            ], 403);
        }

        $output = [];

        // Check if directory exists and is writable
        $directoryChecks = [
            'public/uploads/payments' => public_path('uploads/payments'),
            'storage/app/public/payments' => storage_path('app/public/payments'),
            'storage/app/public/payment_proofs' => storage_path('app/public/payment_proofs'),
        ];

        $output['directories'] = [];

        foreach ($directoryChecks as $name => $path) {
            $output['directories'][$name] = [
                'exists' => is_dir($path),
                'writable' => is_dir($path) && is_writable($path),
                'files_count' => is_dir($path) ? count(glob($path . '/*.*')) : 0,
            ];

            // If directory doesn't exist, try to create it
            if (!is_dir($path)) {
                try {
                    mkdir($path, 0755, true);
                    $output['directories'][$name]['created'] = true;
                    $output['directories'][$name]['exists'] = true;
                    $output['directories'][$name]['writable'] = is_writable($path);
                } catch (\Exception $e) {
                    $output['directories'][$name]['created'] = false;
                    $output['directories'][$name]['error'] = $e->getMessage();
                }
            }
        }

        // Check specific order if ID is provided
        if ($id) {
            try {
                $pesanan = Pesanan::findOrFail($id);
                $output['order'] = [
                    'id' => $pesanan->id_pesanan,
                    'bukti_pembayaran' => $pesanan->bukti_pembayaran,
                ];

                if ($pesanan->bukti_pembayaran) {
                    $fileName = basename($pesanan->bukti_pembayaran);

                    $pathTests = [
                        'uploads/payments/' . $fileName => public_path('uploads/payments/' . $fileName),
                        'direct_path' => public_path($pesanan->bukti_pembayaran),
                        'storage_path' => storage_path('app/public/' . $pesanan->bukti_pembayaran),
                        'payment_proofs_path' => storage_path('app/public/payment_proofs/' . $fileName),
                    ];

                    $output['path_tests'] = [];

                    foreach ($pathTests as $type => $path) {
                        $output['path_tests'][$type] = [
                            'path' => $path,
                            'exists' => file_exists($path),
                            'readable' => file_exists($path) && is_readable($path),
                            'size' => file_exists($path) ? filesize($path) : null,
                            'mime' => file_exists($path) ? mime_content_type($path) : null,
                        ];
                    }

                    // Generated URLs
                    $output['urls'] = [
                        'admin_route' => route('admin.pesanan.payment-proof', ['id' => $pesanan->id_pesanan]),
                        'customer_route' => route('pesanan.payment-proof', ['id' => $pesanan->id_pesanan]),
                        'direct_asset' => asset($pesanan->bukti_pembayaran),
                        'direct_storage' => Str::startsWith($pesanan->bukti_pembayaran, 'payment_proofs/')
                            ? asset('storage/' . $pesanan->bukti_pembayaran)
                            : null,
                    ];
                }

            } catch (\Exception $e) {
                $output['order'] = [
                    'error' => $e->getMessage(),
                ];
            }
        }

        // List a few recent orders with payment proofs
        $recentOrders = Pesanan::whereNotNull('bukti_pembayaran')
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        $output['recent_orders'] = [];

        foreach ($recentOrders as $order) {
            $found = false;
            $foundPath = null;

            // Try to find the actual file
            $fileName = basename($order->bukti_pembayaran);

            if (file_exists(public_path('uploads/payments/' . $fileName))) {
                $found = true;
                $foundPath = 'public/uploads/payments/' . $fileName;
            } elseif (file_exists(public_path($order->bukti_pembayaran))) {
                $found = true;
                $foundPath = 'public/' . $order->bukti_pembayaran;
            } elseif (file_exists(storage_path('app/public/' . $order->bukti_pembayaran))) {
                $found = true;
                $foundPath = 'storage/app/public/' . $order->bukti_pembayaran;
            } elseif (file_exists(storage_path('app/public/payment_proofs/' . $fileName))) {
                $found = true;
                $foundPath = 'storage/app/public/payment_proofs/' . $fileName;
            }

            $output['recent_orders'][] = [
                'id' => $order->id_pesanan,
                'bukti_pembayaran' => $order->bukti_pembayaran,
                'file_found' => $found,
                'found_at' => $foundPath,
                'updated_at' => $order->updated_at->format('Y-m-d H:i:s'),
            ];
        }

        // If we're in debug mode, provide the full response as JSON
        if ($request->has('format') && $request->get('format') === 'json') {
            return response()->json($output);
        }

        // Otherwise provide a nice HTML view
        return view('admin.diagnostic.payment-proofs', [
            'data' => $output,
            'id' => $id
        ]);
    }
}
