<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API routes untuk dropdown alamat
Route::get('/kabupaten/{provinsi_id}', function ($provinsiId) {
    $kabupaten = \App\Models\Kabupaten::where('id_provinsi', $provinsiId)
        ->orderBy('nama_kabupaten')
        ->get(['id_kabupaten', 'nama_kabupaten']);

    return response()->json($kabupaten);
});

Route::get('/kecamatan/{kabupaten_id}', function ($kabupatenId) {
    $kecamatan = \App\Models\Kecamatan::where('id_kabupaten', $kabupatenId)
        ->orderBy('nama_kecamatan')
        ->get(['id_kecamatan', 'nama_kecamatan']);

    return response()->json($kecamatan);
});
