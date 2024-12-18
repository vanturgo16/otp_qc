<?php

use App\Http\Controllers\ppic\workOrderController;
use Illuminate\Support\Facades\Route;

Route::group(
  ['prefix' => 'ppic/workOrder'],
  function () {
    Route::controller(workOrderController::class)->middleware('permission:PPIC_workOrder')->group(function () {
      Route::get('/', 'index')->name('ppic.workOrder.index');
      Route::get('/create', 'create')->name('ppic.workOrder.create');
      Route::get('/create/{encryptedSONumber}', 'createWithSO')->name('ppic.workOrder.createWithSO');
      // Route::get('/get-data', 'getData')->name('ppic.workOrder.getData');
      // Route::get('/get-customers', 'getCustomers')->name('ppic.workOrder.getCustomers');
      Route::get('/get-order-detail', 'getOrderDetail')->name('ppic.workOrder.getOrderDetail');
      Route::get('/generate-wo-number', 'generateWONumber')->name('ppic.workOrder.generateWONumber');
      Route::get('/get-data-product', 'getDataProduct')->name('ppic.workOrder.getDataProduct');
      Route::get('/get-product-detail', 'getProductDetail')->name('ppic.workOrder.getProductDetail');
      Route::get('/get-all-unit', 'getAllUnit')->name('ppic.workOrder.getAllUnit');
      Route::post('/', 'store')->name('ppic.workOrder.store');
      Route::get('/edit/{encryptedWONumber}', 'edit')->name('ppic.workOrder.edit');
      Route::get('/edit-from-list/{encryptedWONumber}', 'edit')->name('ppic.workOrder.editFromList');
      Route::get('/get-data-sales-order', 'getDataSalesOrder')->name('ppic.workOrder.getDataSalesOrder');
      Route::put('/', 'update')->name('ppic.workOrder.update');
      Route::get('/show/{encryptedWONumber}', 'show')->name('ppic.workOrder.view');
      Route::post('/bulk-posted', 'bulkPosted')->name('ppic.workOrder.bulkPosted');
      Route::post('/bulk-unposted', 'bulkUnPosted')->name('ppic.workOrder.bulkUnPosted');
      Route::post('/bulk-deleted', 'bulkDeleted')->name('ppic.workOrder.bulkDeleted');
      // Route::get('/preview/{encryptedWONumber}', 'preview')->name('ppic.workOrder.preview');
      Route::get('/print', 'print')->name('ppic.workOrder.print');
      Route::get('/work-order-list/{encryptedSONumber}', 'workOrderList')->name('ppic.workOrder.list');
      Route::get('/work-order-details/{encryptedWONumber}', 'woDetails')->name('ppic.workOrder.woDetails');
      Route::get('/ajax-wo-details', 'ajaxWODetails')->name('ppic.workOrder.ajaxWODetails');
      Route::get('/edit-wo-detail/{encryptedWONumber}/{encryptedIDRawMaterials}', 'editWODetail')->name('ppic.workOrder.editWODetail');
      Route::get('/show-wo-detail/{encryptedWONumber}/{encryptedIDRawMaterials}', 'showWODetail')->name('ppic.workOrder.viewWODetail');
      Route::get('/create-work-order-details/{encryptedIDRawMaterials}', 'createWODetails')->name('ppic.workOrder.createWODetails');
      Route::get('/get-raw-material', 'getRawMaterial')->name('ppic.workOrder.getRawMaterial');
      Route::post('/store-wo-detail', 'storeWODetail')->name('ppic.workOrder.storeWODetail');
      Route::put('/update-wo-detail', 'updateWODetail')->name('ppic.workOrder.updateWODetail');
      Route::post('/delete-wo-detail', 'deleteWODetail')->name('ppic.workOrder.deleteWODetail');
      Route::get('/get-data-sales-order', 'getDataSalesOrder')->name('ppic.workOrder.getDataSalesOrder');
    });
  }
);
