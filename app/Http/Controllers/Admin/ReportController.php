<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PembayaranController;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Produk;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        // Get selected year, default to current year
        $selectedYear = $request->input('year', Carbon::now()->year);

        // Create date range for the selected year
        $startDate = Carbon::create($selectedYear, 1, 1)->startOfYear();
        $endDate = Carbon::create($selectedYear, 12, 31)->endOfYear();

        // Query for yearly sales data (last 5 years for dropdown)
        $availableYears = Pesanan::select(DB::raw('YEAR(created_at) as year'))
            ->where('status_pesanan', 'Selesai')
            ->groupBy('year')
            ->orderByDesc('year')
            ->pluck('year');

        // Get yearly sales data for selected year by month
        $yearlyData = Pesanan::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(total_harga) as total_revenue'),
            DB::raw('COUNT(*) as order_count')
        )
        ->where('status_pesanan', 'Selesai')
        ->whereYear('created_at', $selectedYear)
        ->groupBy('month')
        ->orderBy('month')
        ->get()
        ->map(function ($item) {
            return [
                'month' => Carbon::create(null, $item->month)->format('F'),
                'total_revenue' => $item->total_revenue,
                'order_count' => $item->order_count
            ];
        });

        // Get top products for the selected year
        $topProducts = DetailPesanan::select(
            'produk.id_Produk',
            'produk.nama_ikan as name',
            'produk.harga',
            DB::raw('SUM(detail_pesanan.kuantitas) as total_sold'),
            DB::raw('SUM(detail_pesanan.subtotal) as total_revenue')
        )
        ->join('produk', 'detail_pesanan.id_Produk', '=', 'produk.id_Produk')
        ->join('pesanan', 'detail_pesanan.id_pesanan', '=', 'pesanan.id_pesanan')
        ->where('pesanan.status_pesanan', 'Selesai')
        ->whereYear('pesanan.created_at', $selectedYear)
        ->groupBy('produk.id_Produk', 'produk.nama_ikan', 'produk.harga')
        ->orderByDesc('total_sold')
        ->limit(10)
        ->get();

        // Get successful transaction history for the selected year
        $transactions = Pesanan::with(['user', 'detailPesanan.produk'])
            ->where('status_pesanan', 'Selesai')
            ->whereYear('created_at', $selectedYear)
            ->orderByDesc('created_at')
            ->paginate(10);

        // Calculate summary statistics for the year
        $summary = [
            'total_revenue' => $yearlyData->sum('total_revenue'),
            'total_orders' => $yearlyData->sum('order_count'),
            'average_order_value' => $yearlyData->sum('order_count') > 0
                ? $yearlyData->sum('total_revenue') / $yearlyData->sum('order_count')
                : 0,
        ];

        return view('admin.reports.sales', compact(
            'yearlyData',
            'topProducts',
            'transactions',
            'summary',
            'selectedYear',
            'availableYears'
        ));
    }

    public function financial(Request $request)
    {
        // Get selected year and month, default to current year and month
        $selectedYear = $request->input('year', Carbon::now()->year);
        $selectedMonth = $request->input('month', Carbon::now()->month);

        // Create date range for filtering
        $startDate = $request->input('start_date')
            ? Carbon::createFromFormat('Y-m-d', $request->input('start_date'))->startOfDay()
            : Carbon::create($selectedYear, $selectedMonth, 1)->startOfMonth();

        $endDate = $request->input('end_date')
            ? Carbon::createFromFormat('Y-m-d', $request->input('end_date'))->endOfDay()
            : Carbon::create($selectedYear, $selectedMonth, 1)->endOfMonth();

        // Get payment method analysis data
        $pembayaranController = new PembayaranController();
        $paymentMethodAnalysis = $pembayaranController->getPaymentMethodAnalysis();

        // Get available years for the year selector
        $availableYears = Pesanan::select(DB::raw('YEAR(created_at) as year'))
            ->where('status_pesanan', 'Selesai')
            ->groupBy('year')
            ->orderByDesc('year')
            ->pluck('year');

        // If no years found, add the current year
        if ($availableYears->isEmpty()) {
            $availableYears->push(Carbon::now()->year);
        }

        // Get monthly revenue data for pie charts
        $monthlyRevenue = Pesanan::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(total_harga) as total_revenue')
        )
        ->where('status_pesanan', 'Selesai')
        ->whereYear('created_at', $selectedYear)
        ->groupBy('month')
        ->orderBy('month')
        ->get()
        ->map(function ($item) {
            return [
                'month' => Carbon::create(null, $item->month)->format('F'),
                'month_num' => $item->month,
                'total_revenue' => $item->total_revenue
            ];
        });

        // Get monthly expense data for pie charts
        $monthlyExpenses = Expense::select(
            DB::raw('MONTH(expense_date) as month'),
            DB::raw('SUM(amount) as total_expense')
        )
        ->whereYear('expense_date', $selectedYear)
        ->groupBy('month')
        ->orderBy('month')
        ->get()
        ->map(function ($item) {
            return [
                'month' => Carbon::create(null, $item->month)->format('F'),
                'month_num' => $item->month,
                'total_expense' => $item->total_expense
            ];
        });

        // Get expense categories data for the selected month
        $expenseCategories = Expense::select(
            'category',
            DB::raw('SUM(amount) as total')
        )
        ->whereYear('expense_date', $selectedYear)
        ->whereMonth('expense_date', $selectedMonth)
        ->groupBy('category')
        ->orderByDesc('total')
        ->get();

        // Get expense history
        $expenseHistory = Expense::where(function($query) use ($startDate, $endDate) {
            $query->whereBetween('expense_date', [$startDate, $endDate]);
        })
        ->orderByDesc('expense_date')
        ->paginate(10);

        // Calculate financial summary
        $totalRevenue = Pesanan::where('status_pesanan', 'Selesai')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_harga');

        $totalExpenses = Expense::whereBetween('expense_date', [$startDate, $endDate])
            ->sum('amount');

        // Calculate gross and net profit
        $grossProfit = $totalRevenue;
        $netProfit = $totalRevenue - $totalExpenses;

        $financialSummary = [
            'total_revenue' => $totalRevenue,
            'gross_profit' => $grossProfit,
            'total_expenses' => $totalExpenses,
            'net_profit' => $netProfit,
            'profit_margin' => $totalRevenue > 0 ? ($netProfit / $totalRevenue) * 100 : 0
        ];

        // Get transactions (completed orders for the current period)
        $transactions = Pesanan::with(['user', 'detailPesanan.produk'])
            ->where('status_pesanan', 'Selesai')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderByDesc('created_at')
            ->paginate(10);

        // Return view with data
        return view('admin.reports.financial', compact(
            'financialSummary',
            'monthlyRevenue',
            'monthlyExpenses',
            'expenseCategories',
            'expenseHistory',
            'selectedYear',
            'selectedMonth',
            'availableYears',
            'startDate',
            'endDate',
            'paymentMethodAnalysis',
            'transactions'
        ));
    }
}
