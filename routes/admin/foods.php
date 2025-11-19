<?php

use App\Http\Controllers\Admin\FoodsController;
use Illuminate\Support\Facades\Route;

Route::resource('foods', FoodsController::class)->names([
    'index' => 'admin.foods.index',
    'create' => 'admin.foods.create',
    'store' => 'admin.foods.store',
    'show' => 'admin.foods.show',
    'edit' => 'admin.foods.edit',
    'update' => 'admin.foods.update',
    'destroy' => 'admin.foods.destroy',
]);
