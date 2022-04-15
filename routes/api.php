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


Route::get('/asset', [AssetController::class, 'index']);
Route::get('/asset/user/{id}', [AssetController::class, 'getByUserID']);
Route::get('/asset/{id}', [AssetController::class, 'show']);
Route::post('/asset', [AssetController::class, 'store']);
Route::put('/asset/{id}', [AssetController::class, 'update']);
Route::delete('/asset/{id}', [AssetController::class, 'delete']);

Route::get('/total-value/{id}', [MarketController::class, 'calculateTotalValue']);
Route::get('/single-currency/', [MarketController::class, 'calculateDifferenceOneCurrency']);
Route::get('/single-asset/{id}', [MarketController::class, 'calculateSingleAsset']);

Route::any('{any}', function(){
    return response()->json([
        'status' => 'error',
        'message' => 'Resource not found'], 404);
})->where('any', '.*')->name('notFound');
