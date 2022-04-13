<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AssetController;
use \App\Http\Controllers\MarketController;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get('/asset', [AssetController::class, 'index']);
Route::get('/asset/{id}', [AssetController::class, 'show']);
Route::post('/asset', [AssetController::class, 'store']);
Route::put('/asset/{id}', [AssetController::class, 'update']);
Route::delete('/asset/{id}', [AssetController::class, 'delete']);

Route::get('/btc/{id}', [MarketController::class, 'calculateTotalValue']);
