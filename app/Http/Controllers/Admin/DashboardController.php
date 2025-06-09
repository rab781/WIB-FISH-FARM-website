<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\User;
use App\Models\Produk;
use App\Models\DetailPesanan;
use App\Models\Pembayaran;
use App\Models\Ulasan;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Revenue and Expense statistics
        $totalRevenue = Pesanan::whereIn('status_pesanan', ['Selesai', 'Dikirim'])
            ->sum('total_harga');

        $totalExpenses = Expense::sum('amount');
        $netIncome = $totalRevenue - $totalExpenses;

        // Current month calculations
        $currentMonth = Carbon::now();
        $currentMonthRevenue = Pesanan::whereIn('status_pesanan', ['Selesai', 'Dikirim'])
            ->whereMonth('created_at', $currentMonth->month)
            ->whereYear('created_at', $currentMonth->year)
            ->sum('total_harga');

        // Last month calculations
        $lastMonth = Carbon::now()->subMonth();
        $lastMonthRevenue = Pesanan::whereIn('status_pesanan', ['Selesai', 'Dikirim'])
            ->whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
            ->sum('total_harga');

        // Calculate revenue growth
        $revenueGrowth = $lastMonthRevenue > 0 ?
            (($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 : 0;

        $currentMonthExpenses = Expense::whereMonth('expense_date', $currentMonth->month)
            ->whereYear('expense_date', $currentMonth->year)
            ->sum('amount');
        $currentMonthNetIncome = $currentMonthRevenue - $currentMonthExpenses;

        $lastMonthExpenses = Expense::whereMonth('expense_date', $lastMonth->month)
            ->whereYear('expense_date', $lastMonth->year)
            ->sum('amount');
        $lastMonthNetIncome = $lastMonthRevenue - $lastMonthExpenses;

        $netIncomeGrowth = $lastMonthNetIncome > 0 ?
            (($currentMonthNetIncome - $lastMonthNetIncome) / $lastMonthNetIncome) * 100 : 0;

        // Order statistics
        $totalOrders = Pesanan::count();
        $currentMonthOrders = Pesanan::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        $lastMonthOrders = Pesanan::whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->count();

        $ordersGrowth = $lastMonthOrders > 0 ?
            (($currentMonthOrders - $lastMonthOrders) / $lastMonthOrders) * 100 : 0;

        // Customer statistics
        $totalCustomers = User::where('is_admin', false)->count();
        $currentMonthCustomers = User::where('is_admin', false)
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        $lastMonthCustomers = User::where('is_admin', false)
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->count();

        $customersGrowth = $lastMonthCustomers > 0 ?
            (($currentMonthCustomers - $lastMonthCustomers) / $lastMonthCustomers) * 100 : 0;

        // Product statistics
        $totalProducts = Produk::count();

        // Monthly sales for last 12 months
        $monthlySales = Pesanan::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(total_harga) as total, COUNT(*) as orders_count')
            ->whereIn('status_pesanan', ['Selesai', 'Dikirim'])
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'month' => Carbon::createFromDate($item->year, $item->month, 1)->format('M Y'),
                    'total' => $item->total,
                    'orders_count' => $item->orders_count
                ];
            });        // Top selling products
        $topProducts = DetailPesanan::select('detail_pesanan.id_Produk', DB::raw('SUM(detail_pesanan.kuantitas) as total_sold'), DB::raw('SUM(detail_pesanan.subtotal) as total_revenue'))
            ->join('pesanan', 'detail_pesanan.id_pesanan', '=', 'pesanan.id_pesanan')
            ->whereIn('pesanan.status_pesanan', ['Selesai', 'Dikirim'])
            ->groupBy('detail_pesanan.id_Produk')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->with('produk')
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->produk->nama_ikan ?? 'Unknown Product',
                    'sold' => $item->total_sold,
                    'revenue' => $item->total_revenue
                ];
            });

        // Recent orders
        $recentOrders = Pesanan::with(['user', 'detailPesanan'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Order status summary
        $orderStatusSummary = Pesanan::selectRaw('status_pesanan, COUNT(*) as count')
            ->groupBy('status_pesanan')
            ->get()
            ->pluck('count', 'status_pesanan');

        // Financial summary
        $pendingRevenue = Pesanan::whereIn('status_pesanan', ['Menunggu Pembayaran', 'Diproses'])
            ->sum('total_harga');

        $refundedAmount = \App\Models\Pengembalian::where('status_pengembalian', 'Selesai')
            ->sum('jumlah_klaim');

        $averageOrderValue = Pesanan::whereIn('status_pesanan', ['Selesai', 'Dikirim'])
            ->avg('total_harga');

        return view('admin.dashboard', compact(
            'totalRevenue',
            'revenueGrowth',
            'totalOrders',
            'ordersGrowth',
            'totalCustomers',
            'customersGrowth',
            'totalProducts',
            'monthlySales',
            'topProducts',
            'recentOrders',
            'orderStatusSummary',
            'pendingRevenue',
            'refundedAmount',
            'averageOrderValue',
            'netIncome',
            'currentMonthNetIncome',
            'lastMonthNetIncome',
            'netIncomeGrowth'
        ));
    }

    public function salesReport(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        $status = $request->get('status');

        $query = Pesanan::with(['user', 'detailPesanan.produk'])
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($status) {
            $query->where('status_pesanan', $status);
        }

        $salesData = $query->orderBy('created_at', 'desc')->paginate(20);        // Summary calculations
        $summary = [
            'total_orders' => $query->count(),
            'total_revenue' => $query->sum('total_harga'),
            'avg_order_value' => $query->avg('total_harga'),
            'total_items' => DetailPesanan::join('pesanan', 'detail_pesanan.id_pesanan', '=', 'pesanan.id_pesanan')
                ->whereBetween('pesanan.created_at', [$startDate, $endDate])
                ->when($status, function($q) use ($status) {
                    $q->where('pesanan.status_pesanan', $status);
                })
                ->sum('detail_pesanan.kuantitas')
        ];        // Daily sales for chart
        $dailySales = Pesanan::selectRaw('DATE(pesanan.created_at) as date, SUM(pesanan.total_harga) as total, COUNT(*) as orders_count')
            ->whereBetween('pesanan.created_at', [$startDate, $endDate])
            ->when($status, function($q) use ($status) {
                $q->where('pesanan.status_pesanan', $status);
            })
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();        // Top products by quantity
        $topProductsByQuantity = DetailPesanan::select('detail_pesanan.id_Produk', DB::raw('SUM(detail_pesanan.kuantitas) as total_quantity'), DB::raw('SUM(detail_pesanan.subtotal) as total_revenue'))
            ->join('pesanan', 'detail_pesanan.id_pesanan', '=', 'pesanan.id_pesanan')
            ->join('produk', 'detail_pesanan.id_Produk', '=', 'produk.id_Produk')
            ->whereBetween('pesanan.created_at', [$startDate, $endDate])
            ->when($status, function($q) use ($status) {
                $q->where('pesanan.status_pesanan', $status);
            })
            ->groupBy('detail_pesanan.id_Produk')
            ->orderBy('total_quantity', 'desc')
            ->limit(10)
            ->get();

        // Top products by revenue
        $topProductsByRevenue = DetailPesanan::select('detail_pesanan.id_Produk', DB::raw('SUM(detail_pesanan.subtotal) as total_revenue'), DB::raw('SUM(detail_pesanan.kuantitas) as total_quantity'))
            ->join('pesanan', 'detail_pesanan.id_pesanan', '=', 'pesanan.id_pesanan')
            ->join('produk', 'detail_pesanan.id_Produk', '=', 'produk.id_Produk')
            ->whereBetween('pesanan.created_at', [$startDate, $endDate])
            ->when($status, function($q) use ($status) {
                $q->where('pesanan.status_pesanan', $status);
            })
            ->groupBy('detail_pesanan.id_Produk')
            ->orderBy('total_revenue', 'desc')
            ->limit(10)
            ->get();

        return view('admin.reports.sales', compact(
            'salesData',
            'summary',
            'dailySales',
            'topProductsByQuantity',
            'topProductsByRevenue'
        ));
    }

    public function financialReport(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        $paymentMethod = $request->get('payment_method');

        $query = Pesanan::with(['user', 'pembayaran'])
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($paymentMethod) {
            $query->whereHas('pembayaran', function($q) use ($paymentMethod) {
                $q->where('metode_pembayaran', $paymentMethod);
            });
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get expenses data
        $totalExpenses = \App\Models\Expense::whereBetween('expense_date', [$startDate, $endDate])
            ->sum('amount');

        $monthlyExpenses = \App\Models\Expense::selectRaw('DATE_FORMAT(expense_date, "%Y-%m") as month, SUM(amount) as total')
            ->where('expense_date', '>=', Carbon::now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        $expenseCategories = \App\Models\Expense::selectRaw('category, SUM(amount) as total')
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->groupBy('category')
            ->orderBy('total', 'desc')
            ->get();

        // Financial summary
        $financialSummary = [
            'total_revenue' => Pesanan::whereIn('status_pesanan', ['Selesai', 'Dikirim'])
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('total_harga'),
            'pending_revenue' => Pesanan::whereIn('status_pesanan', ['Menunggu Pembayaran', 'Diproses'])
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('total_harga'),
            'refunded_amount' => \App\Models\Pengembalian::where('status_pengembalian', 'Selesai')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('jumlah_klaim'),
            'total_expenses' => $totalExpenses,
            'net_revenue' => 0
        ];

        $financialSummary['net_revenue'] = $financialSummary['total_revenue'] - $financialSummary['refunded_amount'] - $financialSummary['total_expenses'];

        // Payment method analysis - Get data from PembayaranController
        $pembayaranController = new \App\Http\Controllers\PembayaranController();
        $paymentMethodAnalysis = $pembayaranController->getPaymentMethodAnalysis();

        // Monthly revenue data
        $monthlyRevenue = Pesanan::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(total_harga) as total')
            ->whereIn('status_pesanan', ['Selesai', 'Dikirim'])
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        return view('admin.reports.financial', compact(
            'financialSummary',
            'paymentMethodAnalysis',
            'monthlyRevenue',
            'monthlyExpenses',
            'expenseCategories',
            'transactions'
        ));
    }
}
