<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['prefix' => 'cron', 'as' => 'cron.'], function (){

    Route::get('/execute-schedule', [App\Http\Controllers\CronController::class, 'ExecuteSchedule']);

    Route::get('/execute-webhook', [App\Http\Controllers\CronController::class, 'ExecuteWebhook']);

    Route::get('/notify-to-user', [App\Http\Controllers\CronController::class, 'notifyToUser']);
    Route::get('/remove-junk-device', [App\Http\Controllers\CronController::class, 'removeJunkDevice']);

});

Route::get('/local/{lang}',function($lang){
     \Session::put('locale', $lang);
     \App::setlocale($lang);
     
     return back();
});

//**======================== Payment Gateway Route Group for merchant ====================**//
Route::group(['middleware' => ['auth', 'web']], function () {
    Route::get('/manual/payment', '\App\Gateway\CustomGateway@status');
});
