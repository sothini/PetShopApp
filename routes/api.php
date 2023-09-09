<?php

use App\Http\Controllers\api\v1\AdminUserController;
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

    Route::middleware(['admin.side'])->group(function () {

        Route::get('admin/user-listing', [AdminUserController::class, 'index']);
        Route::put('admin/user-edit/{uuid}', [AdminUserController::class, 'edit']);
        Route::Delete('admin/user-delete/{uuid}', [AdminUserController::class, 'destroy']);
    });

});
