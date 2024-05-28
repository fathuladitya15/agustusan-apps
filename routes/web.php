<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HouseholdController;
use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware('auth')->group(function() {
    Route::middleware('revalidate')->group(function() {
        Route::get('/home', [HomeController::class, 'index'])->name('home');


        Route::prefix('penduduk')->group(function() {
            Route::get('index',[HouseholdController::class,'index'])->name('penduduk');
            Route::get('data',[HouseholdController::class,'getPenduduk'])->name('get.penduduk');
            Route::delete('delete/{id}',[HouseholdController::class,'deletePenduduk'])->name('delete.penduduk');
        });

        Route::prefix('tagihan')->group(function() {
            Route::get('index',[PaymentController::class,'index'])->name('event');
            Route::get('data',[PaymentController::class,'getEvent'])->name('get.event');
            Route::get('detail-event/{id}',[PaymentController::class,'detailEvent'])->name('detail.event');
            Route::get('detail-event/{id}/data',[PaymentController::class,'getDetailEvent'])->name('get.detail.event');
            Route::get('detail-event/{id}/data-household',[PaymentController::class,'detailEventSearchHousehold'])->name('get.house.hold');
            Route::post('detail-event/save',[PaymentController::class,'createDetailEvent'])->name('crete.detail.event');
            Route::get('detail-event-tagihan/{id}',[PaymentController::class,'getDetailEventPerId'])->name('get.detail.event.id');
            Route::post('detail-event-tagihan/update',[PaymentController::class,'updateDetailEventPerId'])->name('update.detail.event.id');
            Route::get('detail-event-details/{event_id}/{name}/{user_id}',[PaymentController::class,'editDetailEventPerId'])->name('edit.detail.event.id');
            Route::delete('detail-event-delete/{id}/{event_id}',[PaymentController::class,'deleteDetailEventPerId'])->name('delete.detail.event.id');
            // Route::delete('delete/{id}',[PaymentController::class,'deleteEvent'])->name('delete.event');
        });

    });
});
