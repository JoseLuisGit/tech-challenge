<?php

use App\Http\Controllers\Api\GroundRegistryController;
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

Route::get('/price-m2/zip-codes/{zip_code}/aggregate/{type}', [GroundRegistryController::class, 'getGroundPriceM2']);