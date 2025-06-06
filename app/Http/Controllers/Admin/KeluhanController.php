<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Keluhan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KeluhanController extends Controller
{
    /**
     * Display a listing of the keluhan.
     */
    public function index()
    {
        $keluhans = Keluhan::with('user')
                        ->orderBy('status', 'asc')
                        ->orderBy('created_at', 'desc')
                        ->paginate(15);

        return view('admin.keluhan.index', compact('keluhans'));
    }

    /**
     * Display the specified keluhan details.
     */
    public function show($id)
    {
        $keluhan = Keluhan::with('user')->findOrFail($id);
        return view('admin.keluhan.show', compact('keluhan'));
    }

    /**
     * Update the keluhan status and response.
     */
    public function respond(Request $request, $id)
    {
        $validatedData = $request->validate([
            'status' => 'required|in:Belum Diproses,Sedang Diproses,Selesai',
            'respon_admin' => 'required|string',
        ]);

        $keluhan = Keluhan::findOrFail($id);
        $keluhan->status = $validatedData['status'];
        $keluhan->respon_admin = $validatedData['respon_admin'];
        $keluhan->respon_at = now();
        $keluhan->save();

        return redirect()->route('admin.keluhan.show', $keluhan->id)
            ->with('success', 'Tanggapan berhasil disimpan.');
    }
}
