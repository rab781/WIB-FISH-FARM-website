<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\QuarantineLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuarantineController extends Controller
{
    public function index()
    {
        $quarantines = QuarantineLog::with(['pesanan.user'])
                                   ->orderBy('started_at', 'desc')
                                   ->paginate(10);

        $activeQuarantines = QuarantineLog::where('status', 'active')->count();
        $completedToday = QuarantineLog::where('status', 'completed')
                                      ->whereDate('completed_at', today())
                                      ->count();

        return view('admin.quarantine.index', compact('quarantines', 'activeQuarantines', 'completedToday'));
    }

    public function show(QuarantineLog $quarantine)
    {
        $quarantine->load(['pesanan.user', 'pesanan.detailPesanan.produk']);
        return view('admin.quarantine.show', compact('quarantine'));
    }

    public function start(Pesanan $pesanan)
    {
        if ($pesanan->status_pesanan !== 'Diproses') {
            return back()->with('error', 'Pesanan harus dalam status "Diproses" untuk memulai karantina');
        }

        if ($pesanan->is_karantina_active) {
            return back()->with('error', 'Pesanan sudah dalam masa karantina');
        }

        $pesanan->startQuarantine();

        return back()->with('success', 'Karantina 7 hari berhasil dimulai');
    }

    public function addDailyCheck(Request $request, QuarantineLog $quarantine)
    {
        $validator = Validator::make($request->all(), [
            'kondisi_ikan' => 'required|in:sehat,kurang_sehat,sakit',
            'temperature' => 'required|numeric|min:20|max:35',
            'ph_level' => 'required|numeric|min:6|max:8.5',
            'oxygen_level' => 'required|numeric|min:0|max:100',
            'catatan' => 'nullable|string|max:500',
            'foto_kondisi.*' => 'nullable|image|mimes:jpeg,png,jpg|max:1024'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if ($quarantine->status !== 'active') {
            return back()->with('error', 'Karantina sudah tidak aktif');
        }

        $checkData = $request->only([
            'kondisi_ikan', 'temperature', 'ph_level', 'oxygen_level', 'catatan'
        ]);

        // Handle photo uploads
        $fotoKondisi = [];
        if ($request->hasFile('foto_kondisi')) {
            foreach ($request->file('foto_kondisi') as $file) {
                $path = $file->store('quarantine', 'public');
                $fotoKondisi[] = $path;
            }
        }
        $checkData['foto_kondisi'] = $fotoKondisi;

        $quarantine->addDailyCheck($checkData);

        // Add timeline entry
        $quarantine->pesanan->addTimelineEntry(
            'Karantina',
            'Pengecekan Harian Karantina',
            "Kondisi ikan: {$checkData['kondisi_ikan']}, Suhu: {$checkData['temperature']}°C, pH: {$checkData['ph_level']}"
        );

        return back()->with('success', 'Pengecekan harian berhasil ditambahkan');
    }

    public function complete(QuarantineLog $quarantine)
    {
        if ($quarantine->status !== 'active') {
            return back()->with('error', 'Karantina sudah tidak aktif');
        }

        if (!$quarantine->canCompleteQuarantine()) {
            return back()->with('error', 'Karantina belum dapat diselesaikan. Pastikan sudah 7 hari dan ada catatan harian yang cukup');
        }

        $quarantine->pesanan->completeQuarantine();

        return back()->with('success', 'Karantina berhasil diselesaikan. Pesanan siap dikirim');
    }

    public function cancel(Request $request, QuarantineLog $quarantine)
    {
        $validator = Validator::make($request->all(), [
            'alasan' => 'required|string|min:10'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if ($quarantine->status !== 'active') {
            return back()->with('error', 'Karantina sudah tidak aktif');
        }

        $quarantine->update([
            'status' => 'cancelled',
            'notes' => $request->alasan,
            'completed_at' => now()
        ]);

        $quarantine->pesanan->update([
            'status_pesanan' => 'Dibatalkan',
            'is_karantina_active' => false,
            'alasan_pembatalan' => 'Karantina dibatalkan: ' . $request->alasan
        ]);

        // Add timeline entry
        $quarantine->pesanan->addTimelineEntry(
            'Dibatalkan',
            'Karantina Dibatalkan',
            'Karantina dibatalkan dengan alasan: ' . $request->alasan
        );

        return back()->with('success', 'Karantina berhasil dibatalkan');
    }

    public function customerView(Pesanan $pesanan)
    {
        // Ensure user can only view their own order's quarantine
        if ($pesanan->user_id !== \Illuminate\Support\Facades\Auth::id()) {
            abort(403);
        }

        $quarantine = $pesanan->quarantineLog;

        if (!$quarantine) {
            abort(404, 'Data karantina tidak ditemukan');
        }

        return view('customer.quarantine.show', compact('pesanan', 'quarantine'));
    }

    public function dashboard()
    {
        $stats = [
            'active' => QuarantineLog::where('status', 'active')->count(),
            'completed_today' => QuarantineLog::where('status', 'completed')
                                             ->whereDate('completed_at', today())
                                             ->count(),
            'pending_completion' => QuarantineLog::where('status', 'active')
                                                 ->whereDate('scheduled_end_at', '<=', today())
                                                 ->count(),
            'total_this_month' => QuarantineLog::whereMonth('started_at', now()->month)
                                              ->whereYear('started_at', now()->year)
                                              ->count()
        ];

        $recentQuarantines = QuarantineLog::with(['pesanan.user'])
                                         ->where('status', 'active')
                                         ->orderBy('started_at', 'desc')
                                         ->limit(5)
                                         ->get();

        return view('admin.quarantine.dashboard', compact('stats', 'recentQuarantines'));
    }
}
