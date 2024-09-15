<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    dd("admin");
})->name('main');

require __DIR__ . '/common.php';