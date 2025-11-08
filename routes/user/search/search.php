<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\SearchController;

Route::controller(SearchController::class)->group(function () {
    Route::get('/search', 'index')->name('search.index');
    Route::post('/search/by-code', 'searchByCode')->name('search.byCode');
    Route::get('/search/{id}/{type}', 'show')->name('search.show');
});
