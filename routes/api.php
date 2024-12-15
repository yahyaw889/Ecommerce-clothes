<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\ModelsController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductLoveController;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\UserAuthController;
use Illuminate\Support\Facades\Route;




Route::controller(UserAuthController::class)->group(function () {
    Route::post('register','register');
    Route::post('login','login');
    Route::post('logout','logout')->middleware('auth:sanctum');
});

Route::controller(ProductController::class)->prefix('product')->group( function () {
    Route::get('/', 'index');
    Route::get('new_product', 'new_product');
    Route::get('more_sold_products', 'more_sold_products');
    Route::get('high_views_product', 'high_views_product');
    Route::get('{id}', 'show');
    Route::post('search', 'search');
    Route::post('filter', 'filter');
});


Route::controller(OrderController::class)->prefix('order')->group( function () {
    Route::post('/' , 'store');
    Route::get('/showTax' , 'showTax');
})->middleware(['throttle:1,1']);

Route::controller(ProductLoveController::class)->prefix('product_love')->middleware('auth:sanctum')->group( function () {
    Route::get('/' , 'index');
    Route::post('/' , 'store');
    Route::delete('/' , 'destroy');
});


Route::controller(CategoryController::class)->group( function () {
    Route::get('category' , 'index');
    Route::get('brand' , 'brand');
});

Route::controller(GoogleAuthController::class)->middleware('web')->prefix('auth/google')->group(function () {
    Route::get('redirect', 'redirectToGoogle')->name('login.google');
    Route::get('callback',  'handleCallback');
});

Route::get('/social' , [SocialController::class , 'index']);
Route::get('/models' , [ModelsController::class , 'index']);
