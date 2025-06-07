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
    public function index()
    {
        $reviews = Ulasan::with(['user', 'produk', 'adminReplier'])
                         ->orderBy('created_at', 'desc')
                         ->paginate(10);

        $stats = [
            'total' => Ulasan::count(),
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addReply(Request $request, Ulasan $review)
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
}
