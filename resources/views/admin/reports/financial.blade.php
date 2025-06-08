@extends('admin.layouts.app')

@section('title', 'Catatan Keuangan')

@push('styles')
{{-- Existing custom financial CSS files are expected to be here or linked in layouts/app.blade.php --}}
<link rel="stylesheet" href="{{ asset('css/admin/reports/financial-theme.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin/reports/financial-summary-cards.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin/reports/financial-enhancement.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin/reports/financial-data-viz.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin/reports/financial-responsive.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin/reports/financial-print.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin/reports/financial-interactions.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin/reports/financial-state-persistence.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin/reports/financial-skeleton.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin/reports/financial-chart-extensions.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin/reports/financial-animations.css') }}">

<style>
    /* Main Variables for Consistent Design - Adjusted to match overall theme */
    :root {
        /* Primary colors adjusted from admin/layouts/app.blade.php and overall theme */
        --primary-color: #f97316; /* Tailwind orange-500/600 */
        --primary-gradient: linear-gradient(135deg, #f97316 0%, #ea580c 100%); /* Orange gradient */
        --secondary-color: #4b5563; /* Tailwind gray-700 */
        --success-color: #16a34a; /* Tailwind green-600 */
        --success-gradient: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
        --danger-color: #ef4444; /* Tailwind red-500 */
        --danger-gradient: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        --info-color: #3b82f6; /* Tailwind blue-500 */
        --info-gradient: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        --warning-color: #f59e0b; /* Tailwind yellow-500 */
        --warning-gradient: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);

        /* Text colors */
        --text-primary: #1f2937; /* Tailwind gray-900 */
        --text-secondary: #4b5563; /* Tailwind gray-700 */
        --text-tertiary: #6b7280; /* Tailwind gray-500 */

        /* Background colors */
        --background-light: #f3f4f6; /* Tailwind gray-100 */
        --background-white: #ffffff;
        --border-color: #e5e7eb; /* Tailwind gray-200 */

        /* Shadows for dark mode */
        --shadow-sm: 0 1px 3px rgba(0,0,0,0.1), 0 1px 2px rgba(0,0,0,0.06);
        --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
        --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05);

        --card-radius: 0.75rem; /* 12px for consistent rounded corners */
        --transition-speed: 0.2s;
    }

    /* Override any conflicting Bootstrap styles if necessary with Tailwind */
    .container-fluid {
        @apply mx-auto px-4 py-8; /* Tailwind equivalent of container-fluid with padding */
    }
    .row {
        @apply flex flex-wrap -mx-3; /* Tailwind equivalent of Bootstrap row with negative margins */
    }
    .col, [class*="col-"] {
        @apply px-3; /* Tailwind equivalent of Bootstrap col padding */
    }
    .d-flex { @apply flex; }
    .justify-content-between { @apply justify-between; }
    .align-items-center { @apply items-center; }
    .mb-4 { @apply mb-4; }
    .gap-3 { @apply gap-3; }
    .gap-4 { @apply gap-4; }
    .mr-2 { @apply mr-2; }
    .me-2 { @apply mr-2; } /* for Bootstrap compatibility if needed */
    .ms-2 { @apply ml-2; } /* for Bootstrap compatibility if needed */
    .text-sm { @apply text-sm; }
    .fw-semibold { @apply font-semibold; }
    .text-secondary { @apply text-gray-700; } /* Consistent with Tailwind gray-700 */
    .bg-light { @apply bg-gray-100; } /* Consistent with Tailwind gray-100 */
    .border-0 { @apply border-0; }
    .form-select-sm { @apply text-sm; }
    .d-inline { @apply inline; }
    .btn-primary-custom {
        @apply bg-orange-600 text-white px-5 py-2.5 rounded-lg font-semibold hover:bg-orange-700 transition-colors duration-200 flex items-center justify-center;
    }
    .btn-light-custom {
        @apply bg-gray-200 text-gray-800 px-5 py-2.5 rounded-lg font-semibold hover:bg-gray-300 transition-colors duration-200 flex items-center justify-center;
    }

    /* Specific overrides for clarity or consistency */
    .financial-header .page-title {
        @apply text-3xl font-bold;
    }
    .metric-value {
        @apply text-4xl font-extrabold; /* Make these even bolder */
    }
    .financial-summary-card .summary-icon {
        @apply bg-gradient-to-br from-orange-500 to-orange-700; /* Consistent gradient for summary icons */
    }
    .financial-card .card-header h5 {
        @apply text-lg font-bold; /* Consistent font size for card titles */
    }

    /* Ensure specific custom CSS files are loaded correctly */
    /* If you placed the financial-*.css files in public/css/admin/reports/, this is correct. */
    /* Otherwise, adjust the path accordingly. */

    /* Ensure icons are correctly sized */
    .fas, .far, .fab {
        font-size: inherit; /* Inherit font size from parent by default */
    }
    .metric-icon i {
        font-size: 1.5rem; /* Larger icons for metric cards */
    }
    .summary-icon i {
        font-size: 1.25rem; /* Standard size for summary icons */
    }
    .financial-tabs .nav-link i {
        font-size: 1rem;
    }
    .insight-card .fas {
        font-size: 1.25rem;
    }

    /* Ensure custom button styles take precedence */
    .btn-modern {
        @apply inline-flex items-center justify-center rounded-lg px-4 py-2 font-semibold text-sm transition-all duration-300 ease-in-out;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    .btn-modern:hover {
        @apply transform -translate-y-0.5 shadow-md;
    }
    .btn-primary.btn-modern {
        @apply bg-gradient-to-br from-orange-500 to-orange-700 text-white;
    }
    .btn-success.btn-modern {
        @apply bg-gradient-to-br from-green-500 to-green-700 text-white;
    }
    .btn-light.btn-modern {
        @apply bg-gray-200 text-gray-800;
    }

    /* Form control styling */
    .form-control, .form-select {
        @apply border border-gray-300 rounded-lg px-3 py-2 text-gray-800 focus:border-orange-500 focus:ring-1 focus:ring-orange-500;
    }
    .input-group-text {
        @apply bg-gray-100 border border-gray-300 px-3 py-2 rounded-l-lg text-gray-600;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="notification-container" id="notificationContainer">
        </div>

    <div class="print-header hidden md:flex items-center justify-between border-b pb-5 mb-5">
        <img src="{{ asset('Images/Logo_WIB_FISH_FARM.png') }}" alt="Company Logo" class="h-16 mb-3">
        <div class="text-right">
            <h2 class="text-2xl font-bold mb-1">Laporan Keuangan</h2>
            <p class="text-sm text-gray-700">{{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}</p>
            <p class="text-xs text-gray-600">Dibuat pada: {{ date('d M Y H:i') }}</p>
        </div>
    </div>

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="page-title mb-1">Laporan Keuangan</h1>
            <p class="text-gray-700 mb-0">Lacak performa dan analisis keuangan Anda</p>
        </div>
        <div class="flex items-center space-x-3">
            <form id="yearFilterForm" action="{{ route('admin.reports.financial') }}" method="GET" class="inline-flex mx-2">
                <select id="yearFilter" name="year" class="form-select form-select-sm text-sm font-medium border-gray-300 rounded-lg" onchange="this.form.submit()">
                    @foreach($availableYears as $year)
                        <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
            </form>
            <form id="monthFilterForm" action="{{ route('admin.reports.financial') }}" method="GET" class="inline-flex">
                <select id="monthFilter" name="month" class="form-select form-select-sm text-sm font-medium border-gray-300 rounded-lg" onchange="this.form.submit()">
                    @php $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']; @endphp
                    @foreach($months as $index => $month)
                        <option value="{{ $index + 1 }}" {{ ($selectedMonth ?? date('n')) == ($index + 1) ? 'selected' : '' }}>
                            {{ $month }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 mb-8 flex items-center justify-between">
        <div>
            <p class="text-sm font-semibold text-gray-600 uppercase mb-2">Total Saldo Bersih</p>
            <h2 class="text-5xl font-extrabold text-gray-900 leading-tight">
                Rp {{ number_format($financialSummary['net_profit'] ?? 0, 0, ',', '.') }}
            </h2>
            <div class="flex items-center text-sm mt-3" style="color: {{ ($financialSummary['profit_margin'] ?? 0) >= 0 ? 'green' : 'red' }};">
                <i class="fas fa-{{ ($financialSummary['profit_margin'] ?? 0) >= 0 ? 'arrow-up' : 'arrow-down' }} mr-1"></i>
                <span>{{ number_format(abs($financialSummary['profit_margin'] ?? 0), 2) }}% Margin Profit</span>
            </div>
        </div>
        <div class="flex flex-col space-y-3">
            <a href="{{ route('admin.expenses.create', ['year' => $selectedYear, 'month' => $selectedMonth]) }}" class="btn-success btn-modern py-2 px-4 text-sm">
                <i class="fas fa-plus mr-1"></i>
                <span>Tambah Pengeluaran</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 flex items-center space-x-4">
            <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-700 rounded-full flex items-center justify-center text-white text-2xl flex-shrink-0">
                <i class="fas fa-coins"></i>
            </div>
            <div>
                <p class="text-sm font-semibold text-green-600 uppercase mb-1">Total Pendapatan</p>
                <h2 class="text-3xl font-bold text-gray-900">Rp {{ number_format($financialSummary['total_revenue'] ?? 0, 0, ',', '.') }}</h2>
                <div class="flex items-center text-sm mt-1 text-green-600">
                    <i class="fas fa-arrow-up mr-1"></i>
                    <span>Total Penjualan</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 flex items-center space-x-4">
            <div class="w-14 h-14 bg-gradient-to-br from-red-500 to-red-700 rounded-full flex items-center justify-center text-white text-2xl flex-shrink-0">
                <i class="fas fa-file-invoice"></i>
            </div>
            <div>
                <p class="text-sm font-semibold text-red-600 uppercase mb-1">Total Pengeluaran</p>
                <h2 class="text-3xl font-bold text-gray-900">Rp {{ number_format($financialSummary['total_expenses'] ?? 0, 0, ',', '.') }}</h2>
                <div class="flex items-center text-sm mt-1 text-red-600">
                    <i class="fas fa-arrow-down mr-1"></i>
                    <span>Biaya Operasional</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 flex items-center space-x-4">
            <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-700 rounded-full flex items-center justify-center text-white text-2xl flex-shrink-0">
                <i class="fas fa-chart-line"></i>
            </div>
            <div>
                <p class="text-sm font-semibold text-blue-600 uppercase mb-1">Keuntungan Bersih</p>
                <h2 class="text-3xl font-bold text-gray-900">Rp {{ number_format($financialSummary['net_profit'] ?? 0, 0, ',', '.') }}</h2>
                <div class="flex items-center text-sm mt-1" style="color: {{ ($financialSummary['net_profit'] ?? 0) >= 0 ? 'green' : 'red' }};">
                    <i class="fas fa-{{ ($financialSummary['net_profit'] ?? 0) >= 0 ? 'arrow-up' : 'arrow-down' }} mr-1"></i>
                    <span>{{ ($financialSummary['net_profit'] ?? 0) >= 0 ? 'Profit' : 'Loss' }}</span>
                </div>
            </div>
        </div>
    </div>


    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-8">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800 flex items-center">
                <i class="fas fa-file-invoice-dollar mr-3 text-red-600"></i> Riwayat Pengeluaran
            </h3>
            <a href="{{ route('admin.expenses.create', ['year' => $selectedYear, 'month' => $selectedMonth]) }}" class="btn-primary-custom py-2 px-4 text-sm">
                <i class="fas fa-plus mr-2"></i>Tambah Pengeluaran
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Catatan</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($expenseHistory as $expense)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $expense->expense_date instanceof \Carbon\Carbon ? $expense->expense_date->format('d M Y') : \Carbon\Carbon::parse($expense->expense_date)->format('d M Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $expense->expense_date instanceof \Carbon\Carbon ? $expense->expense_date->format('H:i') : \Carbon\Carbon::parse($expense->expense_date)->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $categoryColor = match($expense->category) {
                                        'Gaji' => 'blue', 'Sewa' => 'purple', 'Listrik' => 'yellow',
                                        'Bahan' => 'teal', 'Peralatan' => 'indigo', 'Transportasi' => 'pink',
                                        'Marketing' => 'green', 'Administrasi' => 'gray', 'Lainnya' => 'red',
                                        default => 'orange' // Fallback for 'Operational' or other new categories
                                    };
                                @endphp
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $categoryColor }}-100 text-{{ $categoryColor }}-800">
                                    {{ $expense->category }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $expense->description }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-red-600">Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ Str::limit($expense->notes, 50) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                 {{-- Link Edit --}}
                                <a href="{{ route('admin.expenses.edit', ['expense' => $expense->id, 'year' => $selectedYear, 'month' => $selectedMonth]) }}" class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                {{-- Form Hapus --}}
                                <form class="delete-expense-form" action="{{ route('admin.expenses.destroy', ['expense' => $expense->id]) }}" method="POST" style="display:inline;" data-expense-name="{{ $expense->deskripsi }}">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="year" value="{{ $selectedYear }}">
                                    <input type="hidden" name="month" value="{{ $selectedMonth }}">
                                    <button type="button" class="btn btn-outline-danger btn-sm delete-expense-btn" data-bs-toggle="tooltip" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                <div class="py-4">
                                    <i class="fas fa-receipt text-4xl mb-3 text-gray-300"></i>
                                    <p class="text-lg font-medium text-gray-900">Tidak ada catatan pengeluaran.</p>
                                    <a href="{{ route('admin.expenses.create', ['year' => $selectedYear, 'month' => $selectedMonth]) }}" class="btn-primary-custom mt-4 py-2 px-4 text-sm inline-flex items-center">
                                        <i class="fas fa-plus mr-2"></i>Tambah Pengeluaran Baru
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 flex justify-end">
            {{ $expenseHistory->withQueryString()->links('pagination::tailwind') }}
        </div>
    </div>



    {{-- Floating Action Menu for Report Actions --}}
    <div class="fixed bottom-8 right-8 z-50">
        <button id="reportActionsToggle" class="bg-orange-600 hover:bg-orange-700 text-white rounded-full w-14 h-14 flex items-center justify-center text-2xl shadow-lg transition-all duration-300 transform hover:scale-110">
            <i class="fas fa-chart-bar"></i>
        </button>
        <div id="floatingActionMenu" class="absolute bottom-16 right-0 flex flex-col space-y-3 opacity-0 invisible transition-all duration-300">
            <button class="bg-white text-gray-700 rounded-full w-12 h-12 flex items-center justify-center text-xl shadow-md hover:bg-gray-100" onclick="window.print()" data-bs-toggle="tooltip" title="Cetak Laporan">
                <i class="fas fa-print"></i>
            </button>
            <button class="bg-white text-gray-700 rounded-full w-12 h-12 flex items-center justify-center text-xl shadow-md hover:bg-gray-100" onclick="exportToExcel()" data-bs-toggle="tooltip" title="Ekspor ke Excel">
                <i class="fas fa-file-excel"></i>
            </button>
            <button class="bg-white text-gray-700 rounded-full w-12 h-12 flex items-center justify-center text-xl shadow-md hover:bg-gray-100" data-bs-toggle="tooltip" title="Kustomisasi Laporan">
                <i class="fas fa-sliders-h"></i>
            </button>
            <button class="bg-white text-gray-700 rounded-full w-12 h-12 flex items-center justify-center text-xl shadow-md hover:bg-gray-100" onclick="refreshAllCharts()" data-bs-toggle="tooltip" title="Refresh Data">
                <i class="fas fa-sync-alt"></i>
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script>
    // Helper function for category colors based on your provided colors
    function getCategoryColorClass(category) {
        switch(category) {
            case 'Gaji': return 'blue';
            case 'Sewa': return 'purple';
            case 'Listrik': return 'yellow';
            case 'Bahan': return 'teal';
            case 'Peralatan': return 'indigo';
            case 'Transportasi': return 'pink';
            case 'Marketing': return 'green';
            case 'Administrasi': return 'gray';
            case 'Lainnya': return 'red';
            case 'Operational': return 'orange'; // Added for the quick add expense modal
            case 'Utilities': return 'yellow';
            case 'Inventory': return 'teal';
            case 'Other': return 'red';
            default: return 'gray';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Init Date Range Picker
        $('#dateRange').daterangepicker({
            startDate: moment('{{ $startDate->format('Y-m-d') }}'),
            endDate: moment('{{ $endDate->format('Y-m-d') }}'),
            ranges: {
               'Hari Ini': [moment(), moment()],
               'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
               '7 Hari Terakhir': [moment().subtract(6, 'days'), moment()],
               '30 Hari Terakhir': [moment().subtract(29, 'days'), moment()],
               'Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
               'Bulan Lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            locale: {
                format: 'DD/MM/YYYY',
                applyLabel: 'Terapkan',
                cancelLabel: 'Batal',
                fromLabel: 'Dari',
                toLabel: 'Sampai',
                customRangeLabel: 'Rentang Kustom',
                weekLabel: 'W',
                daysOfWeek: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                firstDay: 1
            },
            opens: 'left'
        }, function(start, end, label) {
            // Update the form fields
            document.getElementById('start_date').value = start.format('YYYY-MM-DD');
            document.getElementById('end_date').value = end.format('YYYY-MM-DD');

            // Get the current selected year and month
            const selectedYear = document.getElementById('yearFilter').value;
            const selectedMonth = document.getElementById('monthFilter').value;

            // Add the current year as hidden field to preserve selection
            const yearInputHidden = document.createElement('input');
            yearInputHidden.type = 'hidden';
            yearInputHidden.name = 'year';
            yearInputHidden.value = selectedYear;

            // Remove any existing hidden inputs first
            const existingYearInput = document.querySelector('#dateRangeForm input[name="year"]');
            if (existingYearInput) {
                existingYearInput.remove();
            }

            document.getElementById('dateRangeForm').appendChild(yearInputHidden);

            // Also add month if needed
            const monthInputHidden = document.createElement('input');
            monthInputHidden.type = 'hidden';
            monthInputHidden.name = 'month';
            monthInputHidden.value = selectedMonth;

            const existingMonthInput = document.querySelector('#dateRangeForm input[name="month"]');
            if (existingMonthInput) {
                existingMonthInput.remove();
            }

            document.getElementById('dateRangeForm').appendChild(monthInputHidden);

            // Submit the form
            document.getElementById('dateRangeForm').submit();
        });


        // Initialize Monthly Revenue vs Expense Chart
        const monthlyRevenueExpenseCtx = document.getElementById('monthlyRevenueExpenseChart').getContext('2d');
        const monthlyRevenueData = @json($monthlyRevenue);
        const monthlyExpensesData = @json($monthlyExpenses);

        const labels = monthlyRevenueData.map(item => item.month);
        const revenues = monthlyRevenueData.map(item => item.total_revenue);
        const expenses = monthlyExpensesData.map(item => item.total_expense);

        new Chart(monthlyRevenueExpenseCtx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Pendapatan',
                        data: revenues,
                        backgroundColor: 'rgba(52, 211, 153, 0.8)', // Tailwind green-400
                        borderColor: 'rgba(52, 211, 153, 1)',
                        borderWidth: 1,
                        borderRadius: 5
                    },
                    {
                        label: 'Pengeluaran',
                        data: expenses,
                        backgroundColor: 'rgba(239, 68, 68, 0.8)', // Tailwind red-500
                        borderColor: 'rgba(239, 68, 68, 1)',
                        borderWidth: 1,
                        borderRadius: 5
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        grid: { display: false },
                        stacked: false // Not stacked, bars side by side
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0, 0, 0, 0.05)' },
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + (value / 1000000).toFixed(1) + 'M';
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: { size: 12, weight: 'bold' }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                            }
                        }
                    }
                },
                animation: {
                    duration: 1500,
                    easing: 'easeOutBounce'
                }
            }
        });

        // Initialize Payment Method Distribution Chart
        const paymentMethodDistributionCtx = document.getElementById('paymentMethodDistributionChart').getContext('2d');
        const paymentMethodAnalysisData = @json($paymentMethodAnalysis);

        const paymentLabels = paymentMethodAnalysisData.map(item => item.method);
        const paymentAmounts = paymentMethodAnalysisData.map(item => item.total_amount);

        const paymentColors = [
            'rgba(59, 130, 246, 0.8)', // Blue
            'rgba(16, 185, 129, 0.8)', // Green
            'rgba(168, 85, 247, 0.8)', // Purple
            'rgba(251, 191, 36, 0.8)', // Yellow
            'rgba(249, 115, 22, 0.8)', // Orange
            'rgba(107, 114, 128, 0.8)' // Gray
        ];

        const paymentDistributionChart = new Chart(paymentMethodDistributionCtx, {
            type: 'doughnut',
            data: {
                labels: paymentLabels,
                datasets: [{
                    data: paymentAmounts,
                    backgroundColor: paymentColors,
                    borderColor: '#ffffff',
                    borderWidth: 2,
                    hoverOffset: 20
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            font: { size: 12 },
                            boxWidth: 20
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const value = context.parsed;
                                const percentage = context.dataset.data.reduce((acc, current) => acc + current, 0) > 0
                                    ? (value / context.dataset.data.reduce((acc, current) => acc + current, 0) * 100).toFixed(1)
                                    : 0;
                                return `${context.label}: Rp ${new Intl.NumberFormat('id-ID').format(value)} (${percentage}%)`;
                            }
                        }
                    }
                },
                animation: {
                    duration: 1500,
                    easing: 'easeOutQuart'
                }
            }
        });

        // Monthly Overview Charts
        const revenuePieCtx = document.getElementById('revenuePieChart').getContext('2d');
        const expensePieCtx = document.getElementById('expensePieChart').getContext('2d');
        const profitPieCtx = document.getElementById('profitPieChart').getContext('2d');

        // Initial data for Monthly Overview charts based on selectedMonth
        let currentMonthRevenue = monthlyRevenueData.find(item => item.month_num === {{ $selectedMonth }})?.total_revenue || 0;
        let currentMonthExpense = monthlyExpensesData.find(item => item.month_num === {{ $selectedMonth }})?.total_expense || 0;
        let currentMonthProfit = currentMonthRevenue - currentMonthExpense;

        const revenuePieChart = new Chart(revenuePieCtx, {
            type: 'pie',
            data: {
                labels: ['Pendapatan Bulanan'], // This chart will just show one segment
                datasets: [{
                    data: [currentMonthRevenue],
                    backgroundColor: ['#f97316'], // Orange
                    borderColor: '#ffffff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: { callbacks: { label: function(context){ return 'Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed); } } } },
                animation: { duration: 1000, easing: 'easeOutQuart' }
            }
        });

        // Fetch expense categories for the initial month to populate the expensePieChart
        function fetchExpenseCategoriesForMonth(month, year) {
            fetch(`/api/expenses/categories?year=${year}&month=${month}`)
                .then(response => response.json())
                .then(data => {
                    const categoryLabels = data.map(item => item.category);
                    const categoryAmounts = data.map(item => item.total);
                    const categoryColors = data.map(item => {
                        const baseColor = getCategoryColorClass(item.category);
                        const tailwindColors = {
                            'blue': 'rgba(59, 130, 246, 0.8)', 'purple': 'rgba(168, 85, 247, 0.8)',
                            'yellow': 'rgba(251, 191, 36, 0.8)', 'teal': 'rgba(79, 209, 197, 0.8)',
                            'indigo': 'rgba(99, 102, 241, 0.8)', 'pink': 'rgba(236, 72, 153, 0.8)',
                            'green': 'rgba(16, 185, 129, 0.8)', 'gray': 'rgba(107, 114, 128, 0.8)',
                            'red': 'rgba(239, 68, 68, 0.8)', 'orange': 'rgba(249, 115, 22, 0.8)'
                        };
                        return tailwindColors[baseColor] || 'rgba(0,0,0,0.5)';
                    });

                    expensePieChart.data.labels = categoryLabels;
                    expensePieChart.data.datasets[0].data = categoryAmounts;
                    expensePieChart.data.datasets[0].backgroundColor = categoryColors;
                    expensePieChart.update();
                })
                .catch(error => console.error('Error fetching expense categories:', error));
        }

        fetchExpenseCategoriesForMonth({{ $selectedMonth }}, {{ $selectedYear }}); // Initial fetch

        const expensePieChart = new Chart(expensePieCtx, {
            type: 'doughnut',
            data: {
                labels: [], // Will be populated by fetch
                datasets: [{
                    data: [], // Will be populated by fetch
                    backgroundColor: [],
                    borderColor: '#ffffff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: { callbacks: { label: function(context){ return `${context.label}: Rp ${new Intl.NumberFormat('id-ID').format(context.parsed)}`; } } } },
                animation: { duration: 1000, easing: 'easeOutQuart' }
            }
        });

        const profitPieChart = new Chart(profitPieCtx, {
            type: 'pie',
            data: {
                labels: ['Laba Bersih', 'Pengeluaran'],
                datasets: [{
                    data: [
                        currentMonthProfit > 0 ? currentMonthProfit : 0,
                        currentMonthExpense
                    ],
                    backgroundColor: ['#10b981', '#f87171'], // Green for profit, Red for expenses
                    borderColor: '#ffffff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: { callbacks: { label: function(context){ return `${context.label}: Rp ${new Intl.NumberFormat('id-ID').format(context.parsed)}`; } } } },
                animation: { duration: 1000, easing: 'easeOutQuart' }
            }
        });

        // Sync forms - ensure monthly filter keeps the selected year
        document.getElementById('yearFilter').addEventListener('change', function() {
            // Add the current year to the month filter form before submitting
            const yearInputHidden = document.createElement('input');
            yearInputHidden.type = 'hidden';
            yearInputHidden.name = 'year';
            yearInputHidden.value = this.value;

            // Remove any existing hidden year input first
            const existingYearInput = document.querySelector('#monthFilterForm input[name="year"]');
            if (existingYearInput) {
                existingYearInput.remove();
            }

            document.getElementById('monthFilterForm').appendChild(yearInputHidden);
            // Form submission is handled by the default onchange behavior
        });

        // When month filter changes, also include the current selected year
        document.getElementById('monthFilter').addEventListener('change', function() {
            // Get the current selected year
            const selectedYear = document.getElementById('yearFilter').value;

            // Add the current year to the month filter form before submitting
            const yearInputHidden = document.createElement('input');
            yearInputHidden.type = 'hidden';
            yearInputHidden.name = 'year';
            yearInputHidden.value = selectedYear;

            // Remove any existing hidden year input first
            const existingYearInput = document.querySelector('#monthFilterForm input[name="year"]');
            if (existingYearInput) {
                existingYearInput.remove();
            }

            document.getElementById('monthFilterForm').appendChild(yearInputHidden);
            // Form submission is handled by the default onchange behavior
        });

        // For chart updates on monthly report page (if available)
        if (document.getElementById('monthSelector')) {
            document.getElementById('monthSelector').addEventListener('change', function() {
                const selectedMonthNum = parseInt(this.value);
                const selectedYearForMonth = document.getElementById('yearFilter').value; // Use the filter year

                // Update monthly stats display
                const monthName = new Date(selectedYearForMonth, selectedMonthNum - 1, 1).toLocaleString('id-ID', { month: 'long' });
                document.getElementById('selectedMonthLabel').textContent = monthName + ' Ringkasan';

                const revenueForSelectedMonth = monthlyRevenueData.find(item => item.month_num === selectedMonthNum)?.total_revenue || 0;
                const expenseForSelectedMonth = monthlyExpensesData.find(item => item.month_num === selectedMonthNum)?.total_expense || 0;
                const profitForSelectedMonth = revenueForSelectedMonth - expenseForSelectedMonth;

                document.getElementById('selectedMonthRevenue').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(revenueForSelectedMonth);
                document.getElementById('selectedMonthExpense').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(expenseForSelectedMonth);
                document.getElementById('selectedMonthProfit').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(profitForSelectedMonth);

                // Update Pie Charts
                revenuePieChart.data.datasets[0].data = [revenueForSelectedMonth];
                revenuePieChart.update();

                // Fetch and update expense categories for the selected month
                fetchExpenseCategoriesForMonth(selectedMonthNum, selectedYearForMonth);

                profitPieChart.data.datasets[0].data = [
                    profitForSelectedMonth > 0 ? profitForSelectedMonth : 0,
                    expenseForSelectedMonth
                ];
                profitPieChart.update();
            });
        }

        // Toggle Floating Action Menu
        const reportActionsToggle = document.getElementById('reportActionsToggle');
        const floatingActionMenu = document.getElementById('floatingActionMenu');

        reportActionsToggle.addEventListener('click', function() {
            floatingActionMenu.classList.toggle('opacity-0');
            floatingActionMenu.classList.toggle('invisible');
            floatingActionMenu.classList.toggle('translate-y-0');
        });

        // Close Floating Action Menu if clicked outside
        document.addEventListener('click', function(event) {
            if (!reportActionsToggle.contains(event.target) && !floatingActionMenu.contains(event.target)) {
                floatingActionMenu.classList.add('opacity-0', 'invisible');
                floatingActionMenu.classList.remove('translate-y-0');
            }
        });

        // Initialize tooltips (if using Bootstrap JS or a custom implementation)
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
          return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Handle filter collapse icon rotation
        const filterCollapse = document.getElementById('filterCollapse');
        const toggleIcon = document.querySelector('[data-toggle-icon]');

        if (filterCollapse && toggleIcon) {
            filterCollapse.addEventListener('show.bs.collapse', function () {
                toggleIcon.classList.add('rotate-180');
            });
            filterCollapse.addEventListener('hide.bs.collapse', function () {
                toggleIcon.classList.remove('rotate-180');
            });
        }
    });

    // Function to export financial report to excel
    window.exportToExcel = function() {
        // Collect filter parameters
        const startDate = document.getElementById('start_date').value;
        const endDate = document.getElementById('end_date').value;
        const year = document.getElementById('filter_year').value;
        const month = document.getElementById('filter_month').value;

        // Construct URL for export (assuming your backend has an export route for financial reports)
        const exportUrl = `{{ route('admin.expenses.export') }}?start_date=${startDate}&end_date=${endDate}&year=${year}&month=${month}`;

        // Show SweetAlert2 for export
        Swal.fire({
            title: 'Ekspor Laporan Keuangan',
            text: 'Memulai ekspor laporan keuangan...',
            icon: 'info',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            customClass: {
                popup: 'rounded-lg'
            }
        }).then(() => {
            // window.location.href = exportUrl; // Uncomment this to trigger actual download if backend is ready
            Swal.fire({
                title: 'Fungsi Belum Tersedia',
                text: 'Fungsi ekspor aktual perlu diimplementasikan di backend.',
                icon: 'warning',
                confirmButtonColor: '#f59e0b',
                confirmButtonText: 'Mengerti',
                customClass: {
                    popup: 'rounded-lg',
                    confirmButton: 'rounded-md'
                }
            });
        });
    };

    // Placeholder for refreshAllCharts - implement actual data fetching/chart re-draw
    window.refreshAllCharts = function() {
        Swal.fire({
            title: 'Refresh Data Chart',
            text: 'Data chart akan di-refresh...',
            icon: 'info',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            customClass: {
                popup: 'rounded-lg'
            }
        }).then(() => {
            Swal.fire({
                title: 'Fungsi Belum Tersedia',
                text: 'Perlu implementasi API data fetching untuk refresh chart.',
                icon: 'warning',
                confirmButtonColor: '#f59e0b',
                confirmButtonText: 'Mengerti',
                customClass: {
                    popup: 'rounded-lg',
                    confirmButton: 'rounded-md'
                }
            });
        });
        // Example:
        // monthlyRevenueExpenseChart.update();
        // paymentDistributionChart.update();
        // revenuePieChart.update();
        // expensePieChart.update();
        // profitPieChart.update();
    };

    // Helper for getCategoryColor (needed if this is called directly from JS in data fetching)
    window.getCategoryColorClass = function(category) {
        switch(category) {
            case 'Gaji': return 'blue';
            case 'Sewa': return 'purple';
            case 'Listrik': return 'yellow';
            case 'Bahan': return 'teal';
            case 'Peralatan': return 'indigo';
            case 'Transportasi': return 'pink';
            case 'Marketing': return 'green';
            case 'Administrasi': return 'gray';
            case 'Lainnya': return 'red';
            case 'Operational': return 'orange';
            case 'Utilities': return 'yellow';
            case 'Inventory': return 'teal';
            case 'Other': return 'red';
            default: return 'gray';
        }
    };

    function openModal() {
        document.getElementById('filterModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('filterModal').classList.add('hidden');
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        let modal = document.getElementById('filterModal');
        if (event.target == modal) {
            closeModal();
        }
    }

    // Handle delete expense confirmations
    document.querySelectorAll('.delete-expense-btn').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            const form = this.closest('.delete-expense-form');
            const expenseName = form.getAttribute('data-expense-name');

            Swal.fire({
                title: 'Hapus Pengeluaran?',
                html: `<div class="text-center"><p class="text-red-600 font-semibold mb-2">⚠️ PERINGATAN ⚠️</p><p>Pengeluaran "${expenseName}" akan dihapus secara permanen dan <strong>tidak dapat dibatalkan</strong>!</p></div>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-lg',
                    confirmButton: 'rounded-md',
                    cancelButton: 'rounded-md'
                },
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return new Promise((resolve) => {
                        setTimeout(() => {
                            resolve();
                        }, 500);
                    });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Menghapus...',
                        text: 'Sedang memproses penghapusan pengeluaran.',
                        icon: 'info',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    form.submit();
                }
            });
        });
    });
</script>
@endpush
@endsection
