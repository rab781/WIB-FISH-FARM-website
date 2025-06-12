<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Pastikan DB di-import

class ExpenseController extends Controller
{
    /**
     * Menampilkan daftar pengeluaran.
     */
    public function index()
    {
        // Mendapatkan filter dari request
        $startDate = request()->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = request()->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $category = request()->get('category');
        $search = request()->get('search'); // Digunakan untuk mencari di deskripsi/kategori

        $query = Expense::query(); // Menggunakan query() untuk membangun query dinamis

        // Terapkan filter tanggal
        if ($startDate && $endDate) {
            $query->whereBetween('expense_date', [$startDate, $endDate]);
        }

        // Terapkan filter kategori
        if ($category) {
            $query->where('category', $category);
        }

        // Terapkan filter pencarian pada deskripsi atau kategori
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', '%' . $search . '%')
                  ->orWhere('category', 'like', '%' . $search . '%');
            });
        }

        // Urutkan dan paginasi hasil
        $expenses = $query->latest('expense_date')->paginate(10);

        // Hitung ringkasan untuk periode yang difilter
        $summaryQuery = Expense::query();
        if ($startDate && $endDate) {
            $summaryQuery->whereBetween('expense_date', [$startDate, $endDate]);
        }
        if ($category) {
            $summaryQuery->where('category', $category);
        }
        $summary = [
            'total_expenses' => $summaryQuery->sum('amount'),
            'expense_count' => $summaryQuery->count(),
            'avg_expense' => $summaryQuery->count() > 0 ? $summaryQuery->sum('amount') / $summaryQuery->count() : 0,
        ];

        // Mendapatkan semua kategori unik untuk dropdown filter
        $categories = Expense::select('category')->distinct()->pluck('category')->toArray();

        // Data untuk chart bulanan (untuk halaman index)
        $monthlyExpenses = Expense::select(
                DB::raw('DATE_FORMAT(expense_date, "%Y-%m") as month_year'), // Gunakan alias unik
                DB::raw('MONTH(expense_date) as month_num'),
                DB::raw('SUM(amount) as total_expense')
            )
            ->whereYear('expense_date', Carbon::parse($endDate)->year) // Filter berdasarkan tahun dari end date
            ->groupBy('month_year', 'month_num')
            ->orderBy('month_num')
            ->get();

        // Data untuk chart distribusi kategori (untuk halaman index)
        $categoryDistribution = Expense::select(
                'category',
                DB::raw('SUM(amount) as total')
            )
            ->whereYear('expense_date', Carbon::parse($endDate)->year) // Filter berdasarkan tahun
            ->groupBy('category')
            ->orderBy('total', 'desc')
            ->get();


        $header = 'Manajemen Pengeluaran';

        return view('admin.expenses.index', compact(
            'expenses',
            'summary',
            'categories',
            'startDate',
            'endDate',
            'category',
            'search', // Teruskan search ke view untuk mempertahankan nilai input
            'monthlyExpenses',
            'categoryDistribution',
            'header'
        ));
    }

    /**
     * Menampilkan form untuk membuat pengeluaran baru.
     */
    public function create(Request $request)
    {
        // Pass any query parameters to the view
        $queryParams = [];
        if ($request->has('year')) {
            $queryParams['year'] = $request->year;
        }
        if ($request->has('month')) {
            $queryParams['month'] = $request->month;
        }

        $header = 'Tambah Pengeluaran Baru';

        return view('admin.expenses.create', compact('queryParams', 'header'));
    }

    /**
     * Menyimpan pengeluaran baru ke storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        $expense = Expense::create([
            'category' => $validated['category'],
            'description' => $validated['description'],
            'amount' => $validated['amount'],
            'expense_date' => Carbon::parse($validated['expense_date']),
            'notes' => $validated['notes'] ?? null,
            // 'creator_id' => auth()->id(), // Jika Anda memiliki creator_id, aktifkan ini
        ]);

        // Cek apakah request berasal dari modal di halaman laporan keuangan
        if ($request->has('from_financial_report')) {
            return response()->json([
                'success' => true,
                'message' => 'Pengeluaran berhasil ditambahkan!',
                'expense' => $expense
            ]);
        }

        // Preserve query parameters for financial report filtering
        $queryParams = [];
        if ($request->has('year')) {
            $queryParams['year'] = $request->year;
        }
        if ($request->has('month')) {
            $queryParams['month'] = $request->month;
        }

        // Check if the request is coming from financial report page
        $referer = $request->headers->get('referer');
        if ($referer && strpos($referer, 'reports/financial') !== false) {
            return redirect()->route('admin.reports.financial', $queryParams)->with('success', 'Pengeluaran berhasil ditambahkan!');
        }

        return redirect()->route('admin.expenses.index', $queryParams)->with('success', 'Pengeluaran berhasil ditambahkan!');
    }

    /**
     * Menampilkan pengeluaran spesifik.
     */
    public function show(string $id)
    {
        $expense = Expense::findOrFail($id);
        return view('admin.expenses.show', compact('expense'));
    }

    /**
     * Menampilkan form untuk mengedit pengeluaran spesifik.
     */
    public function edit(Request $request, string $id)
    {
        $expense = Expense::findOrFail($id);

        // Pass any query parameters to the view
        $queryParams = [];
        if ($request->has('year')) {
            $queryParams['year'] = $request->year;
        }
        if ($request->has('month')) {
            $queryParams['month'] = $request->month;
        }

        return view('admin.expenses.edit', compact('expense', 'queryParams'));
    }

    /**
     * Memperbarui pengeluaran spesifik di storage.
     */
    public function update(Request $request, string $id)
    {
        $expense = Expense::findOrFail($id);

        $validated = $request->validate([
            'category' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        $expense->update([
            'category' => $validated['category'],
            'description' => $validated['description'],
            'amount' => $validated['amount'],
            'expense_date' => Carbon::parse($validated['expense_date']),
            'notes' => $validated['notes'] ?? null,
        ]);

        // Preserve query parameters for financial report filtering
        $queryParams = [];
        if ($request->has('year')) {
            $queryParams['year'] = $request->year;
        }
        if ($request->has('month')) {
            $queryParams['month'] = $request->month;
        }

        // Check if the request is coming from financial report page
        $referer = $request->headers->get('referer');
        if ($referer && strpos($referer, 'reports/financial') !== false) {
            return redirect()->route('admin.reports.financial', $queryParams)->with('success', 'Pengeluaran berhasil diperbarui!');
        }

        return redirect()->route('admin.expenses.index', $queryParams)->with('success', 'Pengeluaran berhasil diperbarui!');
    }

    /**
     * Menghapus pengeluaran spesifik dari storage (soft delete).
     */
    public function destroy(Request $request, string $id)
    {
        $expense = Expense::findOrFail($id);
        $expense->delete(); // Ini akan melakukan soft delete karena ada trait SoftDeletes di model

        // Preserve query parameters for financial report filtering
        $queryParams = [];
        if ($request->has('year')) {
            $queryParams['year'] = $request->year;
        }
        if ($request->has('month')) {
            $queryParams['month'] = $request->month;
        }

        // Check if the request is coming from financial report page
        $referer = $request->headers->get('referer');
        if ($referer && strpos($referer, 'reports/financial') !== false) {
            return redirect()->route('admin.reports.financial', $queryParams)->with('success', 'Pengeluaran berhasil dihapus!');
        }

        return redirect()->route('admin.expenses.index', $queryParams)->with('success', 'Pengeluaran berhasil dihapus!');
    }

    /**
     * Mengekspor pengeluaran ke Excel (mengembalikan JSON data untuk diproses frontend).
     */
    public function export(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        $category = $request->get('category');
        $year = $request->get('year');
        $month = $request->get('month');

        $query = Expense::query();

        // Terapkan filter tanggal
        if ($startDate && $endDate) {
            $query->whereBetween('expense_date', [$startDate, $endDate]);
        }
        // Terapkan filter tahun
        if ($year) {
            $query->whereYear('expense_date', $year);
        }
        // Terapkan filter bulan
        if ($month) {
            $query->whereMonth('expense_date', $month);
        }
        // Terapkan filter kategori
        if ($category) {
            $query->where('category', $category);
        }

        $expenses = $query->orderBy('expense_date', 'desc')->get();

        return response()->json([
            'data' => $expenses,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'year' => $year,
            'month' => $month
        ]);
    }
}
