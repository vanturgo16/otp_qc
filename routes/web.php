<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GrnController;
use App\Http\Controllers\qc\CoaController;
use App\Http\Controllers\qc\HistorystokController;
use App\Http\Controllers\user\PermissionController;
use App\Http\Controllers\user\RoleController;
use App\Http\Controllers\user\UserController;
use App\Http\Controllers\warehouse\WarehouseController;
use App\Http\Controllers\warehouse\DeliveryNoteController;

//PRODUCTION
use App\Http\Controllers\ProductionController;

//Route Login
Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('auth/login', [AuthController::class, 'postlogin'])->name('postlogin')->middleware("throttle:5,2");
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'clear.permission.cache', 'permission:PPIC'])->group(function () {

    //Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::controller(CoaController::class)->middleware('permission:PPIC_Barcode')->group(function () {
        Route::get('/coa', 'index')->name('coa');
        Route::post('/store-coa', 'store')->name('coa.store');
        Route::get('/show-coa/{id}', 'show')->name('show_coa');
        

        Route::get('/print-coa', 'print_coa')->name('print_coa');

        // Route::get('/create-barcode', 'create')->name('barcode.create');
        // Route::post('/store-barcode', 'store')->name('post.create');
        // Route::get('/cange-barcode-so/{id}', 'cange')->name('barcode.cange');
        
        // 
        // Route::get('/print-standar-barcode/{id}', 'print_standar')->name('print_standar');
        // Route::get('/print-broker-barcode/{id}', 'print_broker')->name('print_broker');
        // Route::get('/print-cbc-barcode/{id}', 'print_cbc')->name('print_cbc');
        // Route::get('/table', 'table_print')->name('table_print');
    });

    Route::controller(HistorystokController::class)->middleware('permission:PPIC_Barcode')->group(function () {
        Route::get('/history-stok', 'index')->name('history-stok');
      
    });

   

}); 
