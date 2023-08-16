<?php

use App\Http\Controllers\Api\AccessTokensController;
use App\Http\Controllers\Api\DeliveriesController;
use App\Http\Controllers\Api\ProductsController;
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
    return Auth::guard('sanctum')->user();
});

Route::apiResource('products',ProductsController::class);

Route::post('auth/access-tokens',[AccessTokensController::class,'store'])
        ->middleware('guest:sanctum');

Route::delete('auth/access-tokens/{tokens?}',[AccessTokensController::class,'destroy'])
        ->middleware('auth:sanctum');

Route::get('deliveries/{delivery}', [DeliveriesController::class, 'show']);
Route::put('deliveries/{delivery}', [DeliveriesController::class, 'update']);