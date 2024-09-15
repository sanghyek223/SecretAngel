<?php

/*
|--------------------------------------------------------------------------
| Common Routes
|--------------------------------------------------------------------------
*/
Route::controller(\App\Http\Controllers\Controller::class)->prefix('common')->group(function () {
    /*
     * File Download URL
     * type => only: 단일, zip: 일괄다운(zip)
     * tbl => 테이블
     * sid => sid 값 enCryptString(sid) 로 암호화해서 전송
     */

    Route::get('fileDownload/{type}/{tbl}/{sid}', 'fileDownload')->where('type', 'only|zip')->name("download");
    Route::post('captcha-make', 'captchaMake')->name("captcha.make");
    Route::post('tinyUpload', 'tinyUpload')->name("tinyUpload");
    Route::post('plUpload', 'plUpload')->name("plUpload");
});

/*
|--------------------------------------------------------------------------
| Borad Routes
|--------------------------------------------------------------------------
*/
Route::controller(\App\Http\Controllers\Board\BoardController::class)->middleware('board.check')->prefix('board/{code}')->group(function () {
    Route::get('/', 'index')->name('board');
    Route::get('view/{sid}', 'view')->name('board.view');
    Route::get('upsert/{sid?}', 'upsert')->name('board.upsert');
    Route::post('data', 'data')->name('board.data');
});

/*
|--------------------------------------------------------------------------
| Fallback Routes
|--------------------------------------------------------------------------
*/
Route::fallback([\App\Http\Controllers\FallbackController::class, 'handle']);