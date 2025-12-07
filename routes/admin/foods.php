<?php

use App\Http\Controllers\Admin\FoodsController;
use Illuminate\Support\Facades\Route;

Route::resource('foods', FoodsController::class)->names([
    'index' => 'foods.index',
    'create' => 'foods.create',
    'store' => 'foods.store',
    'show' => 'foods.show',
    'edit' => 'foods.edit',
    'update' => 'foods.update',
    'destroy' => 'foods.destroy',
]);
