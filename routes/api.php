<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CategoryProductController;
use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductImageController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('category')->group(function () {
    Route::get('/', [CategoryController::class, 'index']);
    Route::post('/save', [CategoryController::class, 'store']);
    Route::get('/detail/{id_category}', [CategoryController::class, 'show']);
    Route::post('/update/{id_category}', [CategoryController::class, 'update']);
    Route::delete('/delete/{id_category}', [CategoryController::class, 'destroy']);
});

Route::prefix('category-product')->group(function () {
    Route::get('/', [CategoryProductController::class, 'index']);
    Route::post('/save', [CategoryProductController::class, 'store']);
    Route::get('/detail/{product_id}/{category_id}', [CategoryProductController::class, 'show']);
    Route::post('/update/{product_id}/{category_id}', [CategoryProductController::class, 'update']);
    Route::delete('/delete/{product_id}/{category_id}', [CategoryProductController::class, 'destroy']);
});

Route::prefix('product')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::post('/save', [ProductController::class, 'store']);
    Route::get('/detail/{id_product}', [ProductController::class, 'show']);
    Route::post('/update/{id_product}', [ProductController::class, 'update']);
    Route::delete('/delete/{id_product}', [ProductController::class, 'destroy']);
});

Route::prefix('product-image')->group(function () {
    Route::get('/', [ProductImageController::class, 'index']);
    Route::post('/save', [ProductImageController::class, 'store']);
    Route::get('/detail/{product_id}/{image_id}', [ProductImageController::class, 'show']);
    Route::post('/update/{product_id}/{image_id}', [ProductImageController::class, 'update']);
    Route::delete('/delete/{product_id}/{image_id}', [ProductImageController::class, 'destroy']);
});

Route::prefix('image')->group(function () {
    Route::get('/', [ImageController::class, 'index']);
    Route::post('/save', [ImageController::class, 'store']);
    Route::get('/detail/{id_image}', [ImageController::class, 'show']);
    Route::post('/update/{id_image}', [ImageController::class, 'update']);
    Route::delete('/delete/{id_image}', [ImageController::class, 'destroy']);
});