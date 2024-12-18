<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GrnController;
use App\Http\Controllers\barcode\BarcodeController;
use App\Http\Controllers\barcode\TracabelityController;
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

    Route::middleware('permission:PPIC_good-receipt-note|PPIC_good-lote-number|PPIC_grn-qc|PPIC_external-no-lot')->group(function () {
        Route::post('/simpan_detail_po_fix', [GrnController::class, 'simpan_detail_po_fix'])->name('simpan_detail_po_fix');
        Route::get('/good-receipt-note', [GrnController::class, 'index'])->name('index');
        Route::get('/grn-pr-add', [GrnController::class, 'grn_pr_add'])->name('grn_pr_add');
        Route::get('/grn-po-add', [GrnController::class, 'grn_po_add'])->name('grn_po_add');
        Route::get('/get-data', [GrnController::class, 'get_data'])->name('get_data');
        Route::post('/simpan_pr_grn', [GrnController::class, 'simpan_pr_grn'])->name('simpan_pr_grn');
        Route::post('/simpan_po_grn', [GrnController::class, 'simpan_po_grn'])->name('simpan_po_grn');
        Route::get('/detail-grn-po/{id}', [GrnController::class, 'detail_grn_po'])->name('detail_grn_po');
        Route::get('/detail-grn-pr/{id}', [GrnController::class, 'detail_grn_pr'])->name('detail_grn_pr');
        Route::delete('/hapus_grn_detail/{id}/{idx}', [GrnController::class, 'hapus_grn_detail'])->name('hapus_grn_detail');
        Route::delete('/hapus_grn_detail_po/{id}/{idx}', [GrnController::class, 'hapus_grn_detail_po'])->name('hapus_grn_detail_po');
        Route::delete('/hapus_grn/{id}', [GrnController::class, 'hapus_grn'])->name('hapus_grn');
        Route::post('/simpan_detail_grn/{id}', [GrnController::class, 'simpan_detail_grn'])->name('simpan_detail_grn');
        Route::post('/simpan_detail_grn_po/{id}', [GrnController::class, 'simpan_detail_grn_po'])->name('simpan_detail_grn_po');
        Route::get('/get-edit-grn-pr/{id}', [GrnController::class, 'get_edit_grn_pr'])->name('get_edit_grn_pr');
        Route::get('/print-grn/{receipt_number}', [GrnController::class, 'print_grn'])->name('print_grn');
        Route::put('/posted_grn/{id}', [GrnController::class, 'posted_grn'])->name('posted_grn');
        Route::put('/unposted_grn/{id}', [GrnController::class, 'unposted_grn'])->name('unposted_grn');
        Route::get('/edit-grn/{id}', [GrnController::class, 'edit_grn'])->name('edit_grn');
        Route::get('/edit-detail-ext-no-lot/{id}', [GrnController::class, 'edit_detail_ext_no_lot'])->name('edit_detail_ext_no_lot');
        Route::put('/update_detail_ext_nolot', [GrnController::class, 'update_detail_ext_nolot'])->name('update_detail_ext_nolot');
        Route::get('/edit-grn-item/{id}', [GrnController::class, 'edit_grn_item'])->name('edit_grn_item');
        Route::put('/update_grn_item/{id}', [GrnController::class, 'update_grn_item'])->name('update_grn_item');
        Route::get('/edit-grn-item-smt/{id}', [GrnController::class, 'edit_grn_item_smt'])->name('edit_grn_item_smt');
        Route::get('/edit-grn-item-smt-po/{id}', [GrnController::class, 'edit_grn_item_smt_po'])->name('edit_grn_item_smt_po');
        Route::put('/update_grn_item_smt/{id}', [GrnController::class, 'update_grn_item_smt'])->name('update_grn_item_smt');
        Route::put('/update_grn_item_smt_po/{id}', [GrnController::class, 'update_grn_item_smt_po'])->name('update_grn_item_smt_po');

        Route::get('/good-lote-number', [GrnController::class, 'good_lote_number'])->name('good_lote_number');
        Route::get('/generate-code', [GrnController::class, 'generateCode'])->name('generateCode');
        Route::put('/update_lot_number', [GrnController::class, 'update_lot_number'])->name('update_lot_number');
        Route::get('/good-lote-number-detail/{id}', [GrnController::class, 'good_lote_number_detail'])->name('good_lote_number_detail');
        Route::get('/generateBarcode/{lot_number}', [GrnController::class, 'generateBarcode'])->name('generateBarcode');
        Route::get('/grn-qc', [GrnController::class, 'grn_qc'])->name('grn_qc');
        Route::put('/qc_passed/{id}', [GrnController::class, 'qc_passed'])->name('qc_passed');
        Route::put('/un_qc_passed/{id}', [GrnController::class, 'un_qc_passed'])->name('un_qc_passed');
        Route::get('/external-no-lot', [GrnController::class, 'external_no_lot'])->name('external_no_lot');
        Route::put('/update_ext_lot_number', [GrnController::class, 'update_ext_lot_number'])->name('update_ext_lot_number');
        Route::get('/detail-external-no-lot/{lot_number}', [GrnController::class, 'detail_external_no_lot'])->name('detail_external_no_lot');

        
     
    }); 

    include __DIR__ . '/ppic/workOrder.php';


    Route::controller(BarcodeController::class)->middleware('permission:PPIC_Barcode')->group(function () {
        Route::get('/barcode', 'index')->name('barcode');
        Route::get('/create-barcode', 'create')->name('barcode.create');
        Route::post('/store-barcode', 'store')->name('post.create');
        Route::get('/cange-barcode-so/{id}', 'cange')->name('barcode.cange');
        Route::get('/show-barcode/{id}', 'show')->name('show_barcode');
        Route::get('/print-satuan-standar-barcode/{id}', 'print_satuan_standar')->name('print_satuan_standar');
        Route::get('/print-standar-barcode/{id}', 'print_standar')->name('print_standar');
        Route::get('/print-broker-barcode/{id}', 'print_broker')->name('print_broker');
        Route::get('/print-cbc-barcode/{id}', 'print_cbc')->name('print_cbc');
        Route::get('/table', 'table_print')->name('table_print');
    });

    Route::controller(TracabelityController::class)->middleware('permission:PPIC_Barcode')->group(function () {

        Route::get('/table', 'index')->name('table_print');
    });

    // Permissions route group
    Route::controller(PermissionController::class)->middleware('permission:PPIC_permission.index')->group(function () {
        Route::get('/permission', 'index')->name('permission.index');
        Route::get('/permission/json', 'jsonpermission')->name('permission.json');
        Route::get('/permission/create', 'create')->name('permission.create');
        Route::post('/permission', 'store')->name('permission.store');
    });

    // Role access management route group
    Route::controller(RoleController::class)->middleware('permission:PPIC_role.index')->group(function () {
        Route::get('/role', 'index')->name('role.index');
        Route::get('/role/create', 'create')->name('role.create');
        Route::post('/role', 'store')->name('role.store');
        Route::get('/role/edit/{role}', 'edit')->name('role.edit');
        Route::patch('/role/update/{role}', 'update')->name('role.update');
    });

    Route::controller(UserController::class)->middleware('permission:PPIC_user.index')->group(function () {
        Route::get('/user', 'index')->name('user.index');
        Route::get('/user/edit/{user}', 'edit')->name('user.edit');
        Route::patch('/user/update/{user}', 'update');
        Route::delete('/hapus-user/{user}', 'destroy');
    });

    Route::controller(WarehouseController::class)->group(function () {
        Route::get('/packing-list', 'index')->name('packing-list');
        Route::get('/create-pl', 'create')->name('packing_list.create');
        // Route::post('/store-pl', 'create')->name('packing_list.store');
        Route::get('/get-customers', 'getCustomers')->name('get-customers');
        Route::post('/check-barcode', 'checkBarcode')->name('check-barcode');
        Route::post('/packing-list-store', 'store')->name('packing_list.store');
        Route::post('/remove-barcode', 'removeBarcode')->name('remove-barcode');
        Route::get('/packing-list/{id}/edit', 'edit')->name('packing_list.edit');
        Route::put('/packing-list/{id}/update', 'update')->name('packing_list.update');
        Route::post('/packing-list/remove-barcode', 'removeBarcode')->name('packing_list.remove_barcode');
        Route::post('update-barcode-detail', 'updateBarcodeDetail')->name('update-barcode-detail');
        Route::get('print/{id}', 'printPackingList')->name('packing_list.print');
        Route::get('packing-list/{id}', 'show')->name('packing-list.show');
        Route::put('packing-list/{id}/post', 'post')->name('packing-list.post');
        Route::put('packing-list/{id}/unpost', 'unpost')->name('packing-list.unpost');
        Route::delete('/packing-list/{id}', 'destroy')->name('packing-list.destroy');
        // Route::post('/adjust-stock', 'adjustStock')->name('adjust-stock');
    });

   
    Route::controller(DeliveryNoteController::class)->group(function () {
        Route::get('delivery_notes', 'list')->name('delivery_notes.list');
        Route::get('delivery_notes/create', 'create')->name('delivery_notes.create');
        Route::post('delivery_notes', 'store')->name('delivery_notes.store');
        Route::get('delivery_notes/{id}/add_packing_list', 'addPackingList')->name('delivery_notes.add_packing_list');
        Route::post('delivery_notes/{id}/store_packing_list', 'storePackingList')->name('delivery_notes.store_packing_list');
        Route::get('getPackingListDetails/{id}', 'getPackingListDetails')->name('delivery_notes.getPackingListDetails');
        Route::get('getPackingListsByCustomer/{customerId}', 'getPackingListsByCustomer')->name('getPackingListsByCustomer');
        Route::put('delivery_notes/{id}/post', 'post')->name('delivery_notes.post');
        Route::put('delivery_notes/{id}/unpost', 'unpost')->name('delivery_notes.unpost');
        Route::delete('delivery_notes/{id}', 'destroy')->name('delivery_notes.destroy');
        Route::get('delivery_notes/{id}/show', 'show')->name('delivery_notes.show');
        Route::get('delivery_notes/{id}/edit', 'edit')->name('delivery_notes.edit');
        Route::put('delivery_notes/{id}/update', 'update')->name('delivery_notes.update');
        Route::get('delivery_notes/{id}/print', 'print')->name('delivery_notes.print');
        Route::put('delivery_notes/{packingListId}/update_remark', 'updateRemark')->name('delivery_note_details.updateRemark');
        Route::get('get-customer-addresses/{customerId}/{type}', 'getCustomerAddresses')->name('get-customer-addresses');
        Route::delete('delivery_notes/{id}/delete_packing_list', 'deletePackingList');
        Route::get('print_packing_list/{id}', 'printPackingList')->name('print_packing_list');
    });
}); 
