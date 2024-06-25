<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HouseholdController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\UserController;
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
            Route::post('create',[HouseholdController::class,'store'])->name('penduduk.store');
            Route::get('get-edit/{id}',[HouseholdController::class,'edit'])->name('penduduk.edit');
            Route::post('update',[HouseholdController::class,'update'])->name('penduduk.update');
            Route::get('data',[HouseholdController::class,'getPenduduk'])->name('get.penduduk');
            Route::delete('delete/{id}',[HouseholdController::class,'deletePenduduk'])->name('delete.penduduk');
        });

        Route::prefix('users')->group(function() {
            Route::get('index',[UserController::class,'index'])->name('users.index');
            Route::get('index/data',[UserController::class,'dataUsers'])->name('users.data');
            Route::get('roles',[UserController::class,'getRoles'])->name('users.get.roles');
        });

        Route::prefix('tagihan')->group(function() {
            Route::get('index',[PaymentController::class,'index'])->name('event');
            Route::get('data',[PaymentController::class,'getEvent'])->name('get.event');
            Route::post('data-event',[PaymentController::class,'updateEvent'])->name('update.event');
            Route::get('detail-event/{id}',[PaymentController::class,'detailEvent'])->name('detail.event');
            Route::get('detail-event/{id}/data',[PaymentController::class,'getDetailEvent'])->name('get.detail.event');
            Route::get('detail-event/{id}/data-household',[PaymentController::class,'detailEventSearchHousehold'])->name('get.house.hold');
            Route::post('detail-event/save',[PaymentController::class,'createDetailEvent'])->name('crete.detail.event');
            Route::get('detail-event-tagihan/{id}',[PaymentController::class,'getDetailEventPerId'])->name('get.detail.event.id');
            Route::post('detail-event-tagihan/update',[PaymentController::class,'updateDetailEventPerId'])->name('update.detail.event.id');
            Route::get('detail-event-details/{event_id}/{name}/{user_id}',[PaymentController::class,'editDetailEventPerId'])->name('edit.detail.event.id');
            Route::delete('detail-event-delete/{id}/{event_id}',[PaymentController::class,'deleteDetailEventPerId'])->name('delete.detail.event.id');
            Route::get('detail-event-count-weeks',[PaymentController::class,'countEventPerWeeks'])->name('count.detail.event.weeks');
            // Route::delete('delete/{id}',[PaymentController::class,'deleteEvent'])->name('delete.event');
        });

    });
});
