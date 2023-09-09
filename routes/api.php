<?php

use App\Http\Controllers\api\v1\AdminUserController;
use App\Http\Controllers\api\v1\BrandController;
use App\Http\Controllers\api\v1\CategoryController;
use App\Http\Controllers\api\v1\PaymentController;
use App\Http\Controllers\api\v1\ProductController;
use App\Http\Controllers\api\v1\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {

    //Admin
    Route::post('admin/login', [AdminUserController::class, 'login']);
    Route::post('admin/create', [AdminUserController::class, 'create']);
    Route::get('admin/logout', [AdminUserController::class, 'logout']);

     //User
     Route::post('user/login', [UserController::class, 'login']);
     Route::post('user/create', [UserController::class, 'create']);
     Route::get('user/logout', [UserController::class, 'logout']);

      //Brand
    Route::get('brands', [BrandController::class, 'index']);
    Route::get('brand/{uuid}', [BrandController::class, 'show']);

    //category
    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('category/{uuid}', [CategoryController::class, 'show']);

    //Product
    Route::get('products', [ProductController::class, 'index']);
    Route::get('product/{uuid}', [ProductController::class, 'show']);

     //Payment
     Route::get('payments', [PaymentController::class, 'index']);
     Route::get('payment/{uuid}', [PaymentController::class, 'show']);
 

    Route::middleware(['admin.side'])->group(function () {
        Route::get('admin/user-listing', [AdminUserController::class, 'index']);
        Route::put('admin/user-edit/{uuid}', [AdminUserController::class, 'edit']);
        Route::Delete('admin/user-delete/{uuid}', [AdminUserController::class, 'destroy']);
    });

    Route::middleware(['user.side'])->group(function () {

        Route::get('user', [UserController::class, 'show_user']);
        Route::put('user/edit', [UserController::class, 'edit']);
        Route::Delete('user', [UserController::class, 'delete']);

        Route::post('brand/create', [BrandController::class, 'create']);
        Route::match(['put', 'patch'], 'brand/{uuid}', [BrandController::class, 'edit']);
        Route::delete('brand/{uuid}', [BrandController::class, 'delete']);
        Route::delete('category/{uuid}', [CategoryController::class, 'delete']);

        Route::post('category/create', [CategoryController::class, 'create']);
        Route::match(['put', 'patch'], 'category/{uuid}', [CategoryController::class, 'edit']);
        Route::delete('category/{uuid}', [CategoryController::class, 'delete']);

        Route::post('product/create', [ProductController::class, 'create']);
        Route::match(['put', 'patch'], 'product/{uuid}', [ProductController::class, 'edit']);
        Route::delete('product/{uuid}', [ProductController::class, 'delete']);

        Route::post('payment/create', [PaymentController::class, 'create']);
        Route::match(['put', 'patch'], 'payment/{uuid}', [PaymentController::class, 'edit']);
        Route::delete('payment/{uuid}', [PaymentController::class, 'delete']);

        
    });
});
