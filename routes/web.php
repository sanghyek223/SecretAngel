<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
//    return view('welcome');
    return view('main');
})->name('main');

Route::get('/test', function () {
    dd("?");
});

require __DIR__ . '/common.php';
