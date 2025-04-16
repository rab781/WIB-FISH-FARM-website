<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('LandingPage');
});

Route::fallback(function () {
    return view('404');
});
