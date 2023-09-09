<?php

use App\Http\Controllers\api\v1\AdminUserController;
use App\Http\Controllers\api\v1\BrandController;
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
    });
});
