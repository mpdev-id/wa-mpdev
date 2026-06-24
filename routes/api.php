<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => ['throttle:api']], function (){

    Route::post('create-message','App\Http\Controllers\Api\BulkController@submitRequest')->name('api.create.message');
    Route::get('create-message','App\Http\Controllers\Api\BulkController@submitRequest')->name('api.create.message.get');
    Route::post('/set-device-status/{device_id}/{status}','App\Http\Controllers\Api\BulkController@setStatus');
    Route::post('/send-webhook/{device_id}','App\Http\Controllers\Api\BulkController@webHook');
    Route::post('/ai-response/generate','App\Http\Controllers\Api\AiResponseController@generate');

});

// QRIS Android Webhook — menerima notifikasi transfer masuk dari HP Android
// Diproteksi: token header X-Auth-Token + rate limit 10 req/mnt per IP
Route::post('/qris-webhook', [App\Http\Controllers\Api\QrisWebhookController::class, 'handle'])
     ->middleware('throttle:qris_webhook')
     ->name('qris.webhook');

// QRIS Ping — tes konektivitas dari aplikasi Android (tidak butuh token)
Route::get('/qris-ping', [App\Http\Controllers\Api\QrisWebhookController::class, 'ping'])
     ->middleware('throttle:60,1')
     ->name('qris.ping');
