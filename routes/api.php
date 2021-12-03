<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocalBookingController;
use App\Http\Controllers\LocalBookingControllerTest;
use App\Http\Controllers\LocalBookingControllerCorpTest;
use App\Http\Controllers\LocalBookingCorpGetCustomer;
use App\Http\Controllers\CbmController;
use App\Http\Controllers\getDateTimeNow;

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
Route::post('/v1/booking/local/test', [LocalBookingControllerTest::class, 'convertPayload'])->middleware('log.request');
Route::post('/v1/booking/local/corp/test', [LocalBookingControllerCorpTest::class, 'convertPayload'])->middleware('log.request');
Route::get('/v1/booking/local/corp/get_customer', [LocalBookingCorpGetCustomer::class, 'sendToLBC'])->middleware('log.request');

Route::get('/v1/booking/local/cbm', [CbmController::class, 'computeCbmLocal'])->middleware('log.request');

Route::get('/v1/booking/international/cbm', [CbmController::class, 'computeCbmInternational'])->middleware('log.request');

Route::get('/v1/booking/getNow', [getDateTimeNow::class, 'getNow'])->middleware('log.request');

Route::fallback(function(){
    return response()->json(['message' => 'Endpoint not found.'], 404);
});