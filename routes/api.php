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
Route::get('/kabupaten/{provinsi_id}', function ($provinsi_id) {
    return Kabupaten::where('provinsi_id', $provinsi_id)->get();
});

Route::get('/kecamatan/{kabupaten_id}', function ($kabupaten_id) {
    return Kecamatan::where('kabupaten_id', $kabupaten_id)->get();
});