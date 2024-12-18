@extends('layouts.master')

@section('konten')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Work Order</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">PPIC</a></li>
                                <li class="breadcrumb-item active">Work Order</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="alert alert-success alert-dismissible alert-label-icon label-arrow fade show d-none" role="alert"
                id="alertSuccess">
                <i class="mdi mdi-check-all label-icon"></i><strong>Success</strong> - <span
                    class="alertMessage">{{ session('success') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

            <div class="alert alert-danger alert-dismissible alert-label-icon label-arrow fade show d-none" role="alert"
                id="alertFail">
                <i class="mdi mdi-block-helper label-icon"></i><strong>Failed</strong> - <span
                    class="alertMessage">{{ session('fail') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <a href="#" class="btn btn-success waves-effect btn-label waves-light" data-search = ""
                                id="" onclick="searchByStatus(this)">
                                <i class="mdi mdi-reorder-horizontal label-icon"></i> All Data
                            </a>
                            <a href="{{ route('ppic.workOrder.create') }}"
                                class="btn btn-primary waves-effect btn-label waves-light">
                                <i class="mdi mdi-plus-box label-icon"></i> Add Data
                            </a>
                            <button type="button" class="btn btn-light waves-effect btn-label waves-light"
                                id="modalPrintWO">
                                <i class="mdi mdi-printer label-icon"></i> Print WO by SO
                            </button>
                            <a href="#" class="btn btn-danger waves-effect btn-label waves-light"
                                data-search = "Finish" id="wo_finish" onclick="searchByStatus(this)">
                                <i class="mdi mdi-file-multiple label-icon"></i> WO Finish
                            </a>
                            <a href="#" class="btn btn-danger waves-effect btn-label waves-light"
                                data-search = "Closed" id="wo_closed" onclick="searchByStatus(this)">
                                <i class="mdi mdi-file-multiple label-icon"></i> WO Closed
                            </a>
                        </div>
                        <div class="card-body">
                            <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />

                            <div class="table-responsive">
                                <table id="wo_list" class="table table-hover table-bordered"
                                    style="font-size: small; min-width: 80rem;">
                                    <thead>
                                        <tr>
                                            <th class="align-middle text-center">
                                                <input type="checkbox" id="checkAllRows">
                                            </th>
                                            <th class="align-middle text-center">#</th>
                                            <th class="align-middle text-center" data-name="order_confirmation">
                                                WO<br>Number</th>
                                            <th class="align-middle text-center" data-name="so_number">Sales<br>Order</th>
                                            <th class="align-middle text-center" data-name="date"
                                                style="min-width: 150px;">
                                                Product</th>
                                            <th class="align-middle text-center" data-name="so_type">
                                                Proccess<br>Production
                                            </th>
                                            <th class="align-middle text-center" data-name="customer">Work Center</th>
                                            <th class="align-middle text-center" data-name="salesman">Qty Proccess</th>
                                            <th class="align-middle text-center" data-name="reference_number">
                                                Unit<br>Proccess</th>
                                            <th class="align-middle text-center" style="min-width: 150px;">Product Needed
                                            </th>
                                            <th class="align-middle text-center" data-name="qty_needed">Qty Needed</th>
                                            <th class="align-middle text-center" data-name="unit_needed">Unit Needed</th>
                                            <th class="align-middle text-center">Note</th>
                                            <th class="align-middle text-center" data-name="status">Status</th>
                                            <th class="align-middle text-center">Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Static Backdrop Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Confirm to Posted</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to posted this data?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary waves-effect btn-label waves-light"
                        onclick="bulkPosted()"><i class="mdi mdi-arrow-right-top-bold label-icon"></i>Posted</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Static Backdrop Modal PDF -->
    <div class="modal fade" id="modalPDF" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Preview or Print</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center fs-1">
                    <a href="#" class="btn btn-primary waves-effect waves-light w-sm preview" target="_blank"
                        rel="noopener noreferrer">
                        <i class="mdi mdi-search-web d-block fs-1"></i> Preview
                    </a>
                    <a href="#" class="btn btn-success waves-effect waves-light w-sm print" target="_blank"
                        rel="noopener noreferrer">
                        <i class="mdi mdi-printer d-block fs-1"></i> Print
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Static Backdrop Modal Print WO by SO -->
    <div class="modal fade" id="printWorkOrder" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        role="dialog" aria-labelledby="printWorkOrderLabel" aria-hidden="true">
        <form action="{{ route('ppic.workOrder.print') }}" method="POST" id="" target="_blank">
            @csrf
            @method('GET')
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="printWorkOrderLabel">Print WO by SO</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col">
                                <select class="form-control data-select2" name="id_sales_orders" id="salesOrderSelectPrint"
                                    style="width: 100%" required>
                                    <option value="">** Please select a Sales Orders</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary waves-effect btn-label waves-light">
                            <i class="mdi mdi-printer label-icon"></i> Print
                        </button>
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var i = 1;
            let dataTable = $('#wo_list').DataTable({
                dom: '<"top d-flex"<"position-absolute top-0 end-0 d-flex search-status"fl>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>><"clear:both">',
                initComplete: function(settings, json) {
                    // Setelah DataTable selesai diinisialisasi
                    // Tambahkan elemen kustom ke dalam DOM
                    $('.top').prepend(
                        `<div class='pull-left col-sm-12 col-md-5'><div class="btn-group mb-4"><button type="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-checkbox-multiple-marked-outline"></i> Bulk Actions</button><ul class="dropdown-menu"><li><button class="dropdown-item" data-status="Request" onclick="showModal(this, 'Delete');"><i class="mdi mdi-trash-can"></i> Delete</button></li><li><button class="dropdown-item" data-status="Request" onclick="showModal(this);"><i class="mdi mdi-check-bold"></i> Posted</button></li></ul></div></div>`
                    );
                    $('.search-status').prepend(
                        `<div id="wo_list_filter" class="dataTables_filter"><label><input type="text" class="form-control form-control-sm" id="status_search" placeholder="Search by status" aria-controls="wo_list" readonly></label></div>`
                    );
                },
                processing: true,
                serverSide: true,
                // scrollX: true,
                language: {
                    lengthMenu: "_MENU_",
                    search: "",
                    searchPlaceholder: "Search",
                },
                pageLength: 20,
                lengthMenu: [
                    [5, 10, 20, 25, 50, 100, 200],
                    [5, 10, 20, 25, 50, 100, 200]
                ],
                aaSorting: [
                    [1, 'desc']
                ], // start to sort data in second column 
                ajax: {
                    url: baseRoute + '/ppic/workOrder/',
                    data: function(d) {
                        d.search = $('input[type="search"]').val(); // Kirim nilai pencarian
                        d.status = $('#status_search').val();
                    }
                },
                columns: [{
                        data: 'bulk-action',
                        name: 'bulk-action',
                        // className: 'align-middle text-center',
                        orderable: false,
                        searchable: false
                    }, {
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                        // className: 'align-middle text-center',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'wo_number',
                        name: 'wo_number',
                        // className: 'align-middle text-center',
                        orderable: true,
                    },
                    {
                        data: 'wo_list',
                        name: 'wo_list',
                        // className: 'align-middle text-center',
                        orderable: true,
                    },
                    {
                        data: 'description',
                        name: 'description',
                        // className: 'align-middle text-center',
                        orderable: true,
                    },
                    {
                        data: 'process',
                        name: 'process',
                        // className: 'align-middle text-center',
                        orderable: true,
                    },
                    {
                        data: 'work_center',
                        name: 'work_center',
                        // className: 'align-middle',
                        orderable: true,
                    },
                    {
                        data: 'qty',
                        name: 'qty',
                        // className: 'align-middle',
                        orderable: true,
                    },
                    {
                        data: 'unit',
                        name: 'unit',
                        // className: 'align-middle',
                        orderable: true,
                    },
                    {
                        data: 'description_needed',
                        name: 'description_needed',
                        // orderable: false,
                        // searchable: false
                    },
                    {
                        data: 'qty_needed',
                        name: 'qty_needed',
                        // className: 'align-middle text-center',
                        orderable: true,
                    },
                    {
                        data: 'unit_needed',
                        name: 'unit_needed',
                        // className: 'align-middle text-center',
                        // orderable: false,
                        // searchable: false
                    },
                    {
                        data: 'note',
                        name: 'note',
                        // className: 'align-middle text-center',
                        // orderable: false,
                        // searchable: false
                    },
                    {
                        data: 'status',
                        name: 'status',
                        // className: 'align-middle text-center',
                        // orderable: false,
                        // searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        // className: 'align-middle text-center',
                        orderable: false,
                        searchable: false
                    },
                ],
                createdRow: function(row, data, dataIndex) {
                    // Tambahkan class "table-success" ke tr jika statusnya "Posted"
                    if (data.statusLabel === 'Posted') {
                        $(row).addClass('table-success');
                    } else if (data.statusLabel === 'Closed') {
                        $(row).addClass('table-info');
                    } else if (data.statusLabel === 'Finish') {
                        $(row).addClass('table-primary');
                    }
                },
                bAutoWidth: false,
                columnDefs: [{
                    'orderable': false,
                    'targets': 0
                }], // hide sort icon on header of first column],
            });
        });
    </script>
@endpush
