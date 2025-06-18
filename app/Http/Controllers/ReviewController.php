<?php

namespace App\Http\Controllers;

use App\Models\Ulasan;
use App\Models\Pesanan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function adminIndex()
    {
        $reviews = Ulasan::with(['user', 'produk', 'adminReplier'])
                         ->orderBy('created_at', 'desc')
                         ->paginate(10);

        $stats = [
            'total' => Ulasan::count(),
            'pending' => Ulasan::where('status_review', 'pending')->count(),
            'approved' => Ulasan::where('status_review', 'approved')->count(),
            'rejected' => Ulasan::where('status_review', 'rejected')->count(),
            'with_reply' => Ulasan::whereNotNull('balasan_admin')->count(),
        ];

        return view('admin.reviews.index', compact('reviews', 'stats'));
    }

    public function adminShow(Ulasan $review)
    {
        $review->load(['user', 'produk', 'adminReplier', 'interactions.user']);
        return view('admin.reviews.show', compact('review'));
    }

    public function store(Request $request, Pesanan $pesanan)
    {
        // Ensure user can only review their own orders
        if ($pesanan->user_id !== Auth::id()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk mereview pesanan ini'
                ], 403);
            }
            abort(403);
        }

        // Validate that the order is completed and can be reviewed
        if ($pesanan->status_pesanan !== 'Selesai') {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya pesanan yang telah selesai yang dapat direview'
                ], 400);
            }
            return back()->with('error', 'Hanya pesanan yang telah selesai yang dapat direview');
        }

        // Check if there are still reviewable products
        if ($pesanan->reviewable_products->isEmpty()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Semua produk dalam pesanan ini sudah direview'
                ], 400);
            }
            return back()->with('error', 'Semua produk dalam pesanan ini sudah direview');
        }

        $validator = Validator::make($request->all(), [
            'reviews' => 'required|array|min:1',
            'reviews.*.id_produk' => 'required|exists:produk,id_Produk',
            'reviews.*.rating' => 'required|integer|min:1|max:5',
            'reviews.*.komentar' => 'required|string|min:10|max:1000',
            'reviews.*.foto_review.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak valid',
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        // Keep track of how many reviews were created
        $reviewCount = 0;

        foreach ($request->reviews as $reviewData) {
            // Check if user has ordered this product and it hasn't been reviewed yet
            $productId = $reviewData['id_produk'];

            // Check if this product is in the reviewable products list
            $canReview = $pesanan->reviewable_products
                ->where('id_Produk', $productId)
                ->isNotEmpty();

            if (!$canReview) {
                continue; // Skip products that can't be reviewed
            }

            // Handle photo uploads
            $fotoReview = [];
            if (isset($reviewData['foto_review'])) {
                foreach ($reviewData['foto_review'] as $file) {
                    // Generate unique filename
                    $filename = 'review_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                    // Store file directly to public folder
                    $file->move(public_path('uploads/reviews'), $filename);
                    $fotoReview[] = 'uploads/reviews/' . $filename;
                }
            }

            // Create the review with auto-approval
            Ulasan::create([
                'user_id' => Auth::id(),
                'id_Produk' => $productId,
                'id_pesanan' => $pesanan->id_pesanan, // Link review to specific order
                'rating' => $reviewData['rating'],
                'komentar' => $reviewData['komentar'],
                'foto_review' => $fotoReview,
                'is_verified_purchase' => true,
                'status_review' => 'approved' // Auto-approve all reviews
            ]);

            $reviewCount++;
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "{$reviewCount} review berhasil ditambahkan"
            ]);
        }

        return redirect()->route('reviews.index')->with('success', "{$reviewCount} review berhasil ditambahkan");
    }

    public function addAdminReply(Request $request, Ulasan $review)
    {
        $validator = Validator::make($request->all(), [
            'balasan_admin' => 'required|string|min:10|max:1000'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $review->addAdminReply($request->balasan_admin);

        return back()->with('success', 'Balasan admin berhasil ditambahkan');
    }

    public function updateStatus(Request $request, Ulasan $review)
    {
        $validator = Validator::make($request->all(), [
            'status_review' => 'required|in:pending,approved,rejected'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $review->update(['status_review' => $request->status_review]);

        $statusText = match($request->status_review) {
            'approved' => 'disetujui',
            'rejected' => 'ditolak',
            'pending' => 'ditandai sebagai pending'
        };

        return back()->with('success', "Review berhasil {$statusText}");
    }

    public function toggleInteraction(Request $request, Ulasan $review)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:helpful,not_helpful'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Invalid interaction type']);
        }

        $review->toggleInteraction(Auth::id(), $request->type);

        return response()->json([
            'success' => true,
            'helpful_count' => $review->fresh()->helpful_count,
            'user_interaction' => $review->getUserInteraction(Auth::id())?->interaction_type
        ]);
    }

    public function customerIndex()
    {
        $reviews = Ulasan::where('user_id', Auth::id())
                         ->with(['produk', 'adminReplier'])
                         ->orderBy('created_at', 'desc')
                         ->paginate(10);

        return view('customer.reviews.index', compact('reviews'));
    }

    public function customerShow(Ulasan $review)
    {
        // Ensure user can only view their own reviews
        if ($review->user_id !== Auth::id()) {
            abort(403);
        }

        $review->load(['produk', 'adminReplier']);
        return view('customer.reviews.show', compact('review'));
    }

    public function productReviews(Produk $produk)
    {
        $reviews = $produk->ulasan()
                         ->with(['user', 'adminReplier', 'interactions'])
                         ->orderBy('helpful_count', 'desc')
                         ->orderBy('created_at', 'desc')
                         ->paginate(10);

        $stats = [
            'average_rating' => $produk->ulasan()
                                     ->where('status_review', 'approved')
                                     ->avg('rating'),
            'total_reviews' => $produk->ulasan()
                                    ->where('status_review', 'approved')
                                    ->count(),
            'rating_distribution' => []
        ];

        // Calculate rating distribution
        for ($i = 1; $i <= 5; $i++) {
            $stats['rating_distribution'][$i] = $produk->ulasan()
                                                      ->where('status_review', 'approved')
                                                      ->where('rating', $i)
                                                      ->count();
        }

        return view('products.reviews', compact('produk', 'reviews', 'stats'));
    }

    public function moderate()
    {
        $pendingReviews = Ulasan::where('status_review', 'pending')
                               ->with(['user', 'produk'])
                               ->orderBy('created_at', 'desc')
                               ->paginate(10);

        $recentReviews = Ulasan::where('status_review', 'approved')
                              ->with(['user', 'produk'])
                              ->orderBy('created_at', 'desc')
                              ->limit(5)
                              ->get();

        return view('admin.reviews.moderate', compact('pendingReviews', 'recentReviews'));
    }

    public function bulkModerate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'review_ids' => 'required|array',
            'review_ids.*' => 'exists:ulasan,id_ulasan',
            'action' => 'required|in:approve,reject'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $status = $request->action === 'approve' ? 'approved' : 'rejected';

        Ulasan::whereIn('id_ulasan', $request->review_ids)
              ->update(['status_review' => $status]);

        $actionText = $request->action === 'approve' ? 'disetujui' : 'ditolak';
        $count = count($request->review_ids);

        return back()->with('success', "{$count} review berhasil {$actionText}");
    }

    public function statistics()
    {
        $stats = [
            'total_reviews' => Ulasan::count(),
            'pending_reviews' => Ulasan::where('status_review', 'pending')->count(),
            'approved_reviews' => Ulasan::where('status_review', 'approved')->count(),
            'rejected_reviews' => Ulasan::where('status_review', 'rejected')->count(),
            'reviews_with_admin_reply' => Ulasan::whereNotNull('balasan_admin')->count(),
            'average_rating' => Ulasan::where('status_review', 'approved')->avg('rating'),
            'reviews_today' => Ulasan::whereDate('created_at', today())->count(),
            'reviews_this_month' => Ulasan::whereMonth('created_at', now()->month)
                                         ->whereYear('created_at', now()->year)
                                         ->count()
        ];

        return view('admin.reviews.statistics', compact('stats'));
    }

    /**
     * Public review listing for products
     */
    public function publicIndex(Produk $produk)
    {
        $reviews = $produk->ulasan()
                         ->where('status_review', 'approved')
                         ->with(['user', 'adminReplier', 'interactions'])
                         ->orderBy('helpful_count', 'desc')
                         ->orderBy('created_at', 'desc')
                         ->paginate(15);

        $stats = [
            'average_rating' => $produk->ulasan()
                                     ->where('status_review', 'approved')
                                     ->avg('rating'),
            'total_reviews' => $produk->ulasan()
                                    ->where('status_review', 'approved')
                                    ->count(),
            'rating_distribution' => []
        ];

        // Calculate rating distribution
        for ($i = 1; $i <= 5; $i++) {
            $stats['rating_distribution'][$i] = $produk->ulasan()
                                                      ->where('status_review', 'approved')
                                                      ->where('rating', $i)
                                                      ->count();
        }

        return view('public.reviews.index', compact('produk', 'reviews', 'stats'));
    }

    public function create(Pesanan $pesanan)
    {
        // Ensure user can only create reviews for their own orders
        if ($pesanan->user_id !== Auth::id()) {
            abort(403);
        }

        // Validate that the order is completed
        if ($pesanan->status_pesanan !== 'Selesai') {
            return back()->with('error', 'Hanya pesanan yang telah selesai yang dapat direview');
        }

        // Note: Removed tanggal_diterima requirement - orders with status "Selesai" can be reviewed
        // regardless of whether tanggal_diterima is set, as the status indicates completion

        // Load the order details including products
        $pesanan->load(['detailPesanan.produk']);

        // Check if there are any reviewable products using our new logic
        if ($pesanan->reviewable_products->isEmpty()) {
            return redirect()->route('pesanan.show', $pesanan->id_pesanan)
                ->with('info', 'Semua produk dalam pesanan ini sudah direview');
        }

        // Use our updated template
        return view('customer.reviews.create_updated', compact('pesanan'));
    }
}
