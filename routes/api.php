<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocalBookingController;
use App\Http\Controllers\CbmController;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/v1/booking/local', [LocalBookingController::class, 'convertPayload'])->middleware('log.request');

Route::get('/v1/booking/cbm', [CbmController::class, 'computeCbm'])->middleware('log.request');

Route::fallback(function(){
    return response()->json([
        'message' => 'Endpoint not found.'], 404);
});