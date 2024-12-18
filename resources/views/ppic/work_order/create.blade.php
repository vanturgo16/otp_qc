@extends('layouts.master')

@section('konten')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Add Work Order</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">PPIC</a></li>
                                <li class="breadcrumb-item active">Add Work Order</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            @php
                $so_number = '';
                $isDisabled = '';
            @endphp
            @if (Route::current()->getName() == 'ppic.workOrder.createWithSO')
                @php
                    $so_number = decrypt(Request::segment(4));
                    $isDisabled = 'disabled';
                @endphp
            @endif

            @if (session('success'))
                <div class="alert alert-success alert-dismissible alert-label-icon label-arrow fade show" role="alert">
                    <i class="mdi mdi-check-all label-icon"></i><strong>Success</strong> - {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('fail'))
                <div class="alert alert-danger alert-dismissible alert-label-icon label-arrow fade show" role="alert">
                    <i class="mdi mdi-block-helper label-icon"></i><strong>Failed</strong> - {{ session('fail') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('warning'))
                <div class="alert alert-warning alert-dismissible alert-label-icon label-arrow fade show" role="alert">
                    <i class="mdi mdi-alert-outline label-icon"></i><strong>Warning</strong> - {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('info'))
                <div class="alert alert-info alert-dismissible alert-label-icon label-arrow fade show" role="alert">
                    <i class="mdi mdi-alert-circle-outline label-icon"></i><strong>Info</strong> - {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row pb-3">
                <div class="col-12">
                    <a href="{{ $so_number == '' ? route('ppic.workOrder.index') : URL::previous() }}"
                        class="btn btn-primary waves-effect btn-label waves-light">
                        <i class="mdi mdi-arrow-left label-icon"></i> Back To List Data Work Order
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <form action="{{ route('ppic.workOrder.store') }}" method="POST" id="formWorkOrder">
                            @csrf
                            <div class="card-header">
                                <i class="mdi mdi-file-multiple-outline label-icon"></i> Add Work Order
                            </div>

                            <div class="card-body">
                                <div class="mt-4 mt-lg-0">
                                    <div class="row mb-4 field-wrapper required-field">
                                        <label for="salesOrderSelect" class="col-sm-3 col-form-label">Sales Order</label>
                                        <div class="col-sm-9">
                                            <select class="form-control data-select2" name="id_sales_orders"
                                                id="salesOrderSelect" style="width: 100%" required {{ $isDisabled }}>
                                                <option value="">** Please select a Sales Orders</option>
                                                @foreach ($salesOrders as $data)
                                                    <option value="{{ $data->id }}"
                                                        data-so-number="{{ $data->so_number }}"
                                                        {{ $data->so_number == $so_number ? 'selected' : '' }}>
                                                        {{ $data->so_number }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-4 field-wrapper required-field">
                                        <label for="proccessProductionSelect" class="col-sm-3 col-form-label">Proccess
                                            Production</label>
                                        <div class="col-sm-9">
                                            <select class="form-control data-select2" name="id_master_process_productions"
                                                id="proccessProductionSelect" style="width: 100%" required>
                                                <option value="">** Please select a Proccess Production</option>
                                                @foreach ($proccessProductions as $data)
                                                    <option value="{{ $data->id }}"
                                                        data-code="{{ $data->process_code }}">{{ $data->process_code }} -
                                                        {{ $data->process }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-4 field-wrapper required-field">
                                        <label for="wo_number" class="col-sm-3 col-form-label">WO Number</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="wo_number" id="wo_number"
                                                value="" required readonly>
                                        </div>
                                    </div>
                                    <div class="row mb-4 field-wrapper required-field">
                                        <label for="workCenterSelect" class="col-sm-3 col-form-label">Work Center</label>
                                        <div class="col-sm-9">
                                            <select class="form-control data-select2" name="id_master_work_centers"
                                                id="workCenterSelect" style="width: 100%" required>
                                                <option value="">** Please select a Work Center</option>
                                                @foreach ($workCenters as $data)
                                                    <option value="{{ $data->id }}">{{ $data->work_center_code }} -
                                                        {{ $data->work_center }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-4 field-wrapper required-field">
                                        <label for="typeProductSelect" class="col-sm-3 col-form-label">Type
                                            Product</label>
                                        <div class="col-sm-9">
                                            <select class="form-control data-select2 typeProductSelect" name="type_product"
                                                onchange="fetchProducts(this);" style="width: 100%" required>
                                                <option value="">** Please
                                                    select a Type Product</option>
                                                <option value="WIP">WIP</option>
                                                <option value="FG">FG</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-4 field-wrapper required-field">
                                        <label for="productSelect" class="col-sm-3 col-form-label">Product</label>
                                        <div class="col-sm-9">
                                            <select class="form-control data-select2 productSelect"
                                                name="id_master_products" onchange="fethchProductDetail(this);"
                                                style="width: 100%" required>
                                                <option value="">** Please
                                                    select a Product</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-4 field-wrapper required-field">
                                        <label for="qty" class="col-sm-3 col-form-label">Qty Proccess</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control qty" name="qty" oninput="this.value = this.value.replace(/[^0-9.]/g, '')" required>
                                        </div>
                                    </div>
                                    <div class="row mb-4 field-wrapper required-field">
                                        <label for="unitSelect" class="col-sm-3 col-form-label">Unit Proccess</label>
                                        <div class="col-sm-9">
                                            <select class="form-control data-select2 unitSelect" name="id_master_units"
                                                style="width: 100%" required>
                                                <option value="" selected>**
                                                    Please select a Unit Proccess</option>
                                                @foreach ($units as $unit)
                                                    <option value="{{ $unit->id }}">
                                                        {{ $unit->unit_code }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-4 field-wrapper">
                                        <label for="startDate" class="col-sm-3 col-form-label">Start Date</label>
                                        <div class="col-sm-9">
                                            <input type="date" class="form-control" name="start_date" id="startDate"
                                                value="{{ date('Y-m-d') }}">
                                        </div>
                                    </div>
                                    <div class="row mb-4 field-wrapper">
                                        <label for="finishDate" class="col-sm-3 col-form-label">Finish Date</label>
                                        <div class="col-sm-9">
                                            <input type="date" class="form-control" name="finish_date"
                                                id="finishDate" value="{{ now()->addDays(7)->format('Y-m-d') }}">
                                        </div>
                                    </div>
                                    <div class="row mb-4 field-wrapper">
                                        <label for="note" class="col-sm-3 col-form-label">Note</label>
                                        <div class="col-sm-9">
                                            <textarea class="form-control" name="note" id="note" rows="5"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- <div class="card-header"
                                style="cursor: pointer; padding: 5px; margin-top: -20px; background-color: aliceblue;"
                                id="headerPayment" onclick="toggle('#bodyPayment')">
                                <h4><i class="mdi mdi-checkbox-marked-outline"></i> Material Needed</h4>
                            </div>
                            <div class="card-body" id="bodyPayment">
                                <div class="mt-4 mt-lg-0">
                                    <div class="row mb-4 field-wrapper">
                                        <label for="typeProductMaterialSelect" class="col-sm-3 col-form-label">Type
                                            Product Material</label>
                                        <div class="col-sm-9">
                                            <select class="form-control data-select2 typeProductMaterialSelect"
                                                name="type_product_material" onchange="fetchProductMaterials(this);" style="width: 100%">
                                                <option value="">** Please
                                                    select a Type Product Material</option>
                                                <option value="WIP">WIP</option>
                                                <option value="FG">FG</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-4 field-wrapper">
                                        <label for="productMaterialSelect" class="col-sm-3 col-form-label">Product</label>
                                        <div class="col-sm-9">
                                            <select class="form-control data-select2 productMaterialSelect"
                                                name="id_master_products_material" onchange="fethchProductMaterialDetail(this);"
                                                style="width: 100%">
                                                <option value="">** Please
                                                    select a Product Material</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-4 field-wrapper">
                                        <label for="qtyNeeded" class="col-sm-3 col-form-label">Qty Needed</label>
                                        <div class="col-sm-9">
                                            <input type="number" class="form-control qtyNeeded" name="qty_needed">
                                        </div>
                                    </div>
                                    <div class="row mb-4 field-wrapper">
                                        <label for="unitNeeded" class="col-sm-3 col-form-label">Unit Needed</label>
                                        <div class="col-sm-9">
                                            <select class="form-control data-select2 unitNeeded" name="id_master_units_needed"
                                                style="width: 100%">
                                                <option value="" selected>**
                                                    Please select a Unit Needed</option>
                                                @foreach ($units as $unit)
                                                    <option value="{{ $unit->id }}">
                                                        {{ $unit->unit }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}

                            <div class="card-footer">
                                <div class="row justify-content-end">
                                    <div class="col-sm-9">
                                        <div>
                                            <a href="{{ route('ppic.workOrder.index') }}" class="btn btn-light w-md"><i
                                                    class="fas fa-arrow-left"></i>&nbsp;
                                                Back</a>
                                            <input type="submit" class="btn btn-primary w-md saveWorkOrder"
                                                value="Save & Add More"
                                                name="{{ $so_number != '' ? 'save_add_more_with_so' : 'save_add_more' }}">
                                            <input type="submit" class="btn btn-success w-md saveWorkOrder"
                                                value="Save" name="{{ $so_number != '' ? 'save_with_so' : 'save' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
