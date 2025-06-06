<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembayaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Get payment method analysis for sales report
     */
    public function getPaymentMethodAnalysis()
    {
        // Analyze payment methods and their usage
        $paymentMethodAnalysis = Pesanan::select('metode_pembayaran', DB::raw('count(*) as total_transactions'), DB::raw('sum(total_harga) as total_amount'))
            ->whereNotNull('metode_pembayaran')
            ->groupBy('metode_pembayaran')
            ->get()
            ->map(function ($item) {
                return [
                    'method' => $this->formatPaymentMethod($item->metode_pembayaran),
                    'total_transactions' => $item->total_transactions,
                    'total_amount' => $item->total_amount,
                    'percentage' => 0 // Will be calculated below
                ];
            });

        // Calculate percentages
        $totalTransactions = $paymentMethodAnalysis->sum('total_transactions');
        $paymentMethodAnalysis = $paymentMethodAnalysis->map(function ($item) use ($totalTransactions) {
            $item['percentage'] = $totalTransactions > 0
                ? round(($item['total_transactions'] / $totalTransactions) * 100, 2)
                : 0;
            return $item;
        });

        return $paymentMethodAnalysis;
    }

    /**
     * Format payment method name for display
     */
    private function formatPaymentMethod($method)
    {
        $formats = [
            'transfer_bank' => 'Transfer Bank',
            'qris' => 'QRIS'
        ];

        return $formats[$method] ?? ucfirst(str_replace('_', ' ', $method));
    }
}
