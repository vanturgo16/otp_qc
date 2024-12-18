@extends('layouts.master')

@section('konten')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">View Work Order</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">PPIC</a></li>
                                <li class="breadcrumb-item active">View Work Order</li>
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
                        <form action="{{ route('ppic.workOrder.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="card-header">
                                <i class="mdi mdi-file-multiple-outline label-icon"></i> View Work Order
                            </div>

                            <div class="card-body">
                                <div class="mt-4 mt-lg-0">
                                    <dl class="row">
                                        <dt class="col-sm-3 mb-2"><label>Work Order</label></dt>
                                        <dd class="col-sm-9 mb-2">{{ $work_order_detail->wo_number }}</dd>

                                        <dt class="col-sm-3 mb-2"><label>Type Product</label></dt>
                                        <dd class="col-sm-9 mb-2">{{ $work_order_detail->type_product }}</dd>

                                        <dt class="col-sm-3 mb-2"><label>Product</label></dt>
                                        <dd class="col-sm-9 mb-2">{{ $work_order_detail->rm_code . ' - ' . $work_order_detail->description }}</dd>

                                        <dt class="col-sm-3 mb-2"><label>Qty</label></dt>
                                        <dd class="col-sm-9 mb-2">{{ $work_order_detail->qty }}</dd>

                                        <dt class="col-sm-3 mb-2"><label>Unit</label></dt>
                                        <dd class="col-sm-9 mb-2">{{ $work_order_detail->unit_code . ' - ' . $work_order_detail->unit }}</dd>
                                    </dl>
                                </div>
                                <!-- end row -->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
