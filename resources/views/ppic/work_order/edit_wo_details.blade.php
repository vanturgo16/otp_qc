@extends('layouts.master')

@section('konten')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Edit Work Order Details</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">PPIC</a></li>
                                <li class="breadcrumb-item active">Edit Work Order Details</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

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
                    <a href="{{ route('ppic.workOrder.woDetails', encrypt($work_order_detail->wo_number)) }}"
                        class="btn btn-primary waves-effect btn-label waves-light">
                        <i class="mdi mdi-arrow-left label-icon"></i> Back To List Data Work Order
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <form action="{{ route('ppic.workOrder.updateWODetail') }}" method="POST" id="">
                            @csrf
                            @method('PUT')
                            <div class="card-header">
                                <i class="mdi mdi-file-multiple-outline label-icon"></i> Edit Work Order Details
                            </div>

                            <div class="card-body">
                                <div class="mt-4 mt-lg-0">
                                    <div class="row mb-4 field-wrapper required-field">
                                        <div class="row mb-4 field-wrapper required-field">
                                            <label for="qty" class="col-sm-3 col-form-label">WO Number</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="wo_number" id="wo_number"
                                                    value="{{ $work_order_detail->wo_number }}" readonly required>
                                                <input type="hidden" class="form-control" name="id_work_orders"
                                                    id="id_work_orders" value="{{ $work_order_detail->id_work_orders }}" required>
                                            </div>
                                        </div>
                                        <label for="proccessProductionSelect" class="col-sm-3 col-form-label">Proccess
                                            Production</label>
                                        <div class="col-sm-9">
                                            <input type="hidden" class="form-control" name="id_master_products_old"
                                            id="id_master_products_old" value="{{ $work_order_detail->id_master_products }}" required>
                                            <select class="form-control data-select2" name="id_master_raw_materials"
                                                id="rawMaterialSelect" style="width: 100%" required>
                                                <option value="">** Please select a Product</option>
                                                @foreach ($rawMaterials as $data)
                                                    <option value="{{ $data->id }}" data-code="{{ $data->rm_code }}"
                                                        {{ $data->id == $work_order_detail->id_master_products ? 'selected' : '' }}>
                                                        {{ $data->rm_code }} -
                                                        {{ $data->description }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-4 field-wrapper required-field">
                                        <label for="qty" class="col-sm-3 col-form-label">Qty</label>
                                        <div class="col-sm-9">
                                            <input type="number" class="form-control qty" name="qty" id="qty"
                                                value="{{ $work_order_detail->qty }}" required>
                                        </div>
                                    </div>
                                    <div class="row mb-4 field-wrapper required-field">
                                        <label for="unitSelect" class="col-sm-3 col-form-label">Unit</label>
                                        <div class="col-sm-9">
                                            <select class="form-control data-select2 unitSelect" name="id_master_units"
                                                id="masterUnitSelect" style="width: 100%" required>
                                                <option value="" selected>** Please select a Unit</option>
                                                @foreach ($units as $unit)
                                                    <option value="{{ $unit->id }}"
                                                        {{ $unit->id == $work_order_detail->id_master_units ? 'selected' : '' }}>
                                                        {{ $unit->unit_code . ' - ' . $unit->unit }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <div class="row justify-content-end">
                                    <div class="col-sm-9">
                                        <div>
                                            <a href="{{ route('ppic.workOrder.index') }}" class="btn btn-light w-md"><i
                                                    class="fas fa-arrow-left"></i>&nbsp;
                                                Back</a>
                                            <input type="submit" class="btn btn-success w-md saveWorkOrder"
                                                value="Save" name="save">
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
