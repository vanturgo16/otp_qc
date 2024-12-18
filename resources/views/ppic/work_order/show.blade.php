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
                    <a href="{{ route('ppic.workOrder.index') }}"
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
                                        <dt class="col-sm-3 mb-2"><label>Sales Order</label></dt>
                                        <dd class="col-sm-9 mb-2">{{ $work_order->so_number }}</dd>

                                        <dt class="col-sm-3 mb-2"><label>Process Production</label></dt>
                                        <dd class="col-sm-9 mb-2">
                                            {{ $work_order->masterProcessProduction->process_code . ' - ' . $work_order->masterProcessProduction->process }}
                                        </dd>

                                        <dt class="col-sm-3 mb-2"><label>WO Number</label></dt>
                                        <dd class="col-sm-9 mb-2">{{ $work_order->wo_number }}</dd>

                                        <dt class="col-sm-3 mb-2"><label>Work Center</label></dt>
                                        <dd class="col-sm-9 mb-2">
                                            {{ $work_order->masterWorkCenter == null ? '' : $work_order->masterWorkCenter->work_center }}
                                        </dd>

                                        <dt class="col-sm-3 mb-2"><label>Type Product</label></dt>
                                        <dd class="col-sm-9 mb-2">{{ $work_order->type_product }}</dd>

                                        <dt class="col-sm-3 mb-2"><label>Product</label></dt>
                                        @php
                                            $perforasi = $product->perforasi == null ? '-' : $product->perforasi;
                                        @endphp
                                        <dd class="col-sm-9 mb-2">
                                            {{ $product->description . ' | Perforasi: ' . $perforasi }}</dd>

                                        <dt class="col-sm-3 mb-2"><label>Qty Process</label></dt>
                                        <dd class="col-sm-9 mb-2">{{ $work_order->qty }}</dd>

                                        <dt class="col-sm-3 mb-2"><label>Unit Process</label></dt>
                                        <dd class="col-sm-9 mb-2">{{ $work_order->masterUnit->unit_code }}</dd>

                                        <dt class="col-sm-3 mb-2"><label>Start Date</label></dt>
                                        <dd class="col-sm-9 mb-2">
                                            {{ \Carbon\Carbon::parse($work_order->start_date)->isoFormat('dddd, D MMMM YYYY', 'ID') }}
                                        </dd>

                                        <dt class="col-sm-3 mb-2"><label>Finish Date</label></dt>
                                        <dd class="col-sm-9 mb-2">
                                            {{ \Carbon\Carbon::parse($work_order->finish_date)->isoFormat('dddd, D MMMM YYYY', 'ID') }}
                                        </dd>

                                        <dt class="col-sm-3 mb-2"><label>Note</label></dt>
                                        <dd class="col-sm-9 mb-2">{{ $work_order->note }}</dd>

                                        <dt class="col-sm-3 mb-2"><label>Type Product Material</label></dt>
                                        <dd class="col-sm-9 mb-2">
                                            {{ $productNeeded == null ? '-' : $work_order->type_product_material }}
                                        </dd>

                                        <dt class="col-sm-3 mb-2"><label>Product Material</label></dt>
                                        @php
                                            $perforasiNeeded =
                                                $productNeeded == null
                                                    ? '-'
                                                    : ($productNeeded->perforasi == null
                                                        ? '-'
                                                        : $productNeeded->perforasi);
                                        @endphp
                                        <dd class="col-sm-9 mb-2">
                                            {{ $productNeeded == null ? '-' : $productNeeded->description . ' | Perforasi: ' . $perforasiNeeded }}
                                        </dd>

                                        <dt class="col-sm-3 mb-2"><label>Qty Needed</label></dt>
                                        <dd class="col-sm-9 mb-2">{{ $work_order->qty_needed }}</dd>

                                        <dt class="col-sm-3 mb-2"><label>Unit Needed</label></dt>
                                        <dd class="col-sm-9 mb-2">
                                            {{ $work_order->masterUnitNeeded == null ? '' : $work_order->masterUnitNeeded->unit_code }}
                                        </dd>
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
