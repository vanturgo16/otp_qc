// ...existing use statements...
<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataSampleController;
use App\Http\Controllers\DataWasteController;
use App\Http\Controllers\GrnController;
use App\Http\Controllers\HistoryStockSampleController;
use App\Http\Controllers\LptsController;
use App\Http\Controllers\LmtsController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\qc\CoaController;
use App\Http\Controllers\qc\HistorystokController;
use App\Http\Controllers\ReturnCustomerPPIC;
use App\Http\Controllers\user\PermissionController;
use App\Http\Controllers\user\RoleController;
use App\Http\Controllers\user\UserController;


//PRODUCTION
use App\Http\Controllers\warehouse\DeliveryNoteController;
use App\Http\Controllers\warehouse\WarehouseController;
use Illuminate\Support\Facades\Route;


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
    });

    Route::controller(HistorystokController::class)->middleware('permission:PPIC_Barcode')->group(function () {
        Route::get('/history-stok', 'index')->name('history-stok');

    });

    Route::controller(DataSampleController::class)->group(function () {
        Route::prefix('data-sample')->group(function () {
            Route::get('/', 'index')->name('sample.index');
            Route::post('/update/{id_so}', 'update')->name('sample.update');
            Route::get('/print-pdf/{id_so}', 'printPdf')->name('sample.printPdf');
            Route::get('/export-excel', [App\Http\Controllers\DataSampleController::class, 'exportExcel'])->name('sample.exportExcel');
        });
    });

    Route::controller(LptsController::class)->group(function () {
        Route::prefix('lpts')->group(function () {
            Route::get('/', 'index')->name('lpts.index');
            Route::post('/keterangan', [LptsController::class, 'storeKeterangan'])->name('lpts.keterangan');
            Route::get('/print/{work_order_id}', [LptsController::class, 'printLpts'])->name('lpts.print');
            Route::get('/export-excel', [LptsController::class, 'exportExcel'])->name('lpts.exportExcel');
            Route::post('/{id}/scrap', [LptsController::class, 'scrap'])->name('lpts.scrap');
            Route::post('/{id}/rework', [LptsController::class, 'rework'])->name('lpts.rework');
        });
    });

    // LMTS routes
    Route::controller(LmtsController::class)->group(function () {
        Route::prefix('lmts')->group(function () {
            Route::get('/', 'index')->name('lmts.index');
            Route::get('/export-excel', 'exportExcel')->name('lmts.export.excel');
            Route::post('/{id}/scrap', 'scrap')->name('lmts.scrap');
            Route::post('/{id}/return', 'return')->name('lmts.return');
            Route::post('/{id}/unposted', 'unposted')->name('lmts.unposted')->middleware('role:Super Admin');
            // Future actions: store, rework, print
        });
    });

    Route::controller(ReturnCustomerPPIC::class)->group(function () {
        Route::prefix('return-customer-ppic')->group(function () {
            Route::get('/', 'index')->name('return-customer-ppic.index');
            Route::post('/store', [ReturnCustomerPPIC::class, 'store'])->name('return-customer-ppic.store');
            Route::get('/print/{hash}', [ReturnCustomerPPIC::class, 'printReturn'])->name('return-customer-ppic.print');
            Route::post('/{id}/scrap', [ReturnCustomerPPIC::class, 'scrap'])->name('return-customer-ppic.scrap');
            Route::post('/{id}/rework', [ReturnCustomerPPIC::class, 'rework'])->name('return-customer-ppic.rework');
            Route::get('/export-excel', [ReturnCustomerPPIC::class, 'exportExcel'])->name('return-customer-ppic.exportExcel');
        });
    });
    Route::controller(DataWasteController::class)->group(function () {
        Route::prefix('data-waste')->group(function () {
            Route::get('/', 'index')->name('data-waste.index');
            Route::get('/print', [DataWasteController::class, 'print'])->name('data-waste.print');
            Route::get('/export-excel', [DataWasteController::class, 'exportExcel'])->name('data-waste.exportExcel');
            Route::post('/store', [DataWasteController::class, 'storeAddWaste'])->name('data-waste.store');


        });
    });

   Route::controller(HistoryStockSampleController::class)->group(function () {
        Route::prefix('historystock')->group(function () {
            Route::get('/fg', 'index')->name('historystock.fg');
            Route::get('/fg/{hash}', 'show')->name('historystock.fg.show');
            Route::post('/fg/export', 'export')->name('historystock.fg.export');



         });
    });
});
