<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ulasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    /**
     * Display a listing of the reviews.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Ulasan::with(['user', 'produk', 'adminReplier']);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', '%' . $search . '%');
                })->orWhereHas('produk', function($produkQuery) use ($search) {
                    $produkQuery->where('nama_ikan', 'like', '%' . $search . '%');
                })->orWhere('komentar', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        if ($request->filled('replied')) {
            if ($request->replied === 'yes') {
                $query->whereNotNull('balasan_admin');
            } else {
                $query->whereNull('balasan_admin');
            }
        }

        $reviews = $query->orderBy('created_at', 'desc')->paginate(10);

        // Get order information for each review
        foreach ($reviews as $review) {
            // Try to find the most recent order for this product by this user
            $detailPesanan = \App\Models\DetailPesanan::whereHas('pesanan', function($q) use ($review) {
                $q->where('user_id', $review->user_id)
                  ->whereIn('status_pesanan', ['Selesai', 'Dikirim']);
            })
            ->where('id_Produk', $review->id_Produk)
            ->with(['pesanan', 'produk'])
            ->latest()
            ->first();

            $review->detailPesanan = $detailPesanan;
            $review->pesanan = $detailPesanan ? $detailPesanan->pesanan : null;
        }

        $stats = [
            'total' => Ulasan::count(),
            'avg_rating' => Ulasan::avg('rating'),
            'with_reply' => Ulasan::whereNotNull('balasan_admin')->count(),
            'without_reply' => Ulasan::whereNull('balasan_admin')->count(),
            'positive' => Ulasan::where('rating', '>=', 4)->count(),
            'neutral' => Ulasan::where('rating', 3)->count(),
            'negative' => Ulasan::where('rating', '<', 3)->count(),
        ];

        return view('admin.reviews.index', compact('reviews', 'stats'));
    }

    /**
     * Display the specified review.
     *
     * @param  \App\Models\Ulasan  $review
     * @return \Illuminate\View\View
     */
    public function show(Ulasan $review)
    {
        $review->load(['user', 'produk', 'adminReplier', 'interactions.user']);
        return view('admin.reviews.show', compact('review'));
    }

    /**
     * Add an admin reply to a review.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ulasan  $review
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function addReply(Request $request, Ulasan $review)
    {
        $validator = Validator::make($request->all(), [
            'balasan_admin' => 'required|string|min:10|max:1000'
        ]);

        if ($validator->fails()) {
            // Handle AJAX requests with JSON response
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        try {
            $review->addAdminReply($request->balasan_admin);

            // Handle AJAX requests with JSON response
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Balasan admin berhasil ditambahkan',
                    'reply' => $review->fresh()->balasan_admin
                ]);
            }

            return back()->with('success', 'Balasan admin berhasil ditambahkan');
        } catch (\Exception $e) {
            // Handle AJAX requests with JSON response
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menyimpan balasan'
                ], 500);
            }

            return back()->with('error', 'Terjadi kesalahan saat menyimpan balasan');
        }
    }

    /**
     * Get detailed review data for modal display.
     *
     * @param  \App\Models\Ulasan  $review
     * @return \Illuminate\Http\JsonResponse
     */
    public function detail(Ulasan $review)
    {
        try {
            $review->load(['user', 'produk', 'adminReplier', 'interactions.user']);

            // Get order information for this review
            $detailPesanan = \App\Models\DetailPesanan::whereHas('pesanan', function($q) use ($review) {
                $q->where('user_id', $review->user_id)
                  ->whereIn('status_pesanan', ['Selesai', 'Dikirim']);
            })
            ->where('id_Produk', $review->id_Produk)
            ->with(['pesanan', 'produk'])
            ->latest()
            ->first();

            // Attach order data to review
            $review->detailPesanan = $detailPesanan;
            $review->pesanan = $detailPesanan ? $detailPesanan->pesanan : null;

            // Get product info prioritizing order data, fallback to direct product relationship
            $productInfo = null;
            if ($detailPesanan && $detailPesanan->produk) {
                $productInfo = [
                    'nama' => $detailPesanan->produk->nama_ikan ?? 'Produk tidak ditemukan',
                    'harga' => $detailPesanan->harga ?? 0,
                    'jumlah' => $detailPesanan->jumlah ?? 0,
                    'foto' => $detailPesanan->produk->foto ?? '/images/default-product.png'
                ];
            } elseif ($review->produk) {
                $productInfo = [
                    'nama' => $review->produk->nama_ikan ?? 'Produk tidak ditemukan',
                    'harga' => $review->produk->harga ?? 0,
                    'jumlah' => 1,
                    'foto' => $review->produk->foto ?? '/images/default-product.png'
                ];
            }

            $data = [
                'id_ulasan' => $review->id_ulasan,
                'rating' => $review->rating,
                'comment' => $review->komentar,
                'status' => $review->status ?? 'active',
                'status_label' => ucfirst($review->status ?? 'active'),
                'is_verified' => $review->is_verified ?? false,
                'created_at' => $review->created_at->format('d M Y H:i'),
                'updated_at' => $review->updated_at->format('d M Y H:i'),
                'customer_name' => $review->user->name ?? 'User tidak ditemukan',
                'customer_email' => $review->user->email ?? 'Email tidak tersedia',
                'customer_avatar' => $review->user->foto ?? '/images/default-avatar.png',
                'product_name' => $productInfo ? $productInfo['nama'] : 'Produk tidak ditemukan',
                'product_image' => $productInfo ? $productInfo['foto'] : '/images/default-product.png',
                'product_price' => $productInfo ? $productInfo['harga'] : 0,
                'product_quantity' => $productInfo ? $productInfo['jumlah'] : 0,
                'order_id' => $review->pesanan ? ($review->pesanan->kode_pesanan ?? $review->pesanan->id) : 'N/A',
                'order_date' => $review->pesanan && $review->pesanan->created_at ? $review->pesanan->created_at->format('d M Y') : 'N/A',
                'admin_reply' => $review->balasan_admin,
                'admin_reply_date' => $review->balasan_admin_at ? $review->balasan_admin_at->format('d M Y H:i') : null,
                'admin_replier' => $review->adminReplier ? [
                    'name' => $review->adminReplier->name ?? 'Admin',
                    'email' => $review->adminReplier->email ?? ''
                ] : null,
                'interactions' => [
                    'helpful' => $review->interactions()->where('interaction_type', 'helpful')->count(),
                    'not_helpful' => $review->interactions()->where('interaction_type', 'not_helpful')->count(),
                    'total' => $review->interactions()->count()
                ],
                'photos' => $this->formatPhotos($review->foto_review)
            ];

            return response()->json([
                'success' => true,
                'review' => $data
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail review: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get review photos for gallery display.
     *
     * @param  \App\Models\Ulasan  $review
     * @return \Illuminate\Http\JsonResponse
     */
    public function photos(Ulasan $review)
    {
        try {
            $photos = $this->formatPhotos($review->foto_review);

            return response()->json([
                'success' => true,
                'photos' => $photos,
                'total' => count($photos),
                'review_id' => $review->id_ulasan,
                'user_name' => $review->user->name ?? 'User tidak ditemukan'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil foto review: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper method to format photos for API response
     */
    private function formatPhotos($fotoReview)
    {
        $photos = [];

        if ($fotoReview) {
            // Parse foto_review data properly
            $photoData = is_string($fotoReview) ? json_decode($fotoReview, true) : $fotoReview;

            // Handle single photo case
            if (!is_array($photoData)) {
                $photoData = [$fotoReview];
            }

            foreach ($photoData as $photo) {
                // Skip empty or invalid photos
                if (empty($photo) || $photo === null || trim($photo) === '' || $photo === 'null') {
                    continue;
                }

                // Check if file exists
                $filePath = storage_path('app/public/' . $photo);
                if (!file_exists($filePath)) {
                    continue;
                }

                // Format photo URL properly
                $photoUrl = asset('storage/' . $photo);
                $photos[] = $photoUrl;
            }
        }

        return $photos;
    }
}
