<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('LandingPage');
});
Route::get('/daftar', function () {
    return view('Daftar');
});
Route::get('/masuk', function () {
    return view('Masuk');
});
Route::post('/daftar', 'RegisterController@register');
Route::fallback(function () {
    return view('404');
});
