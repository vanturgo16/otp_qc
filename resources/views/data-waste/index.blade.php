@extends('layouts.master')
@section('konten')
    <div class="page-content">
        <div class="container-fluid">
            @if (session('pesan'))
                <div class="alert alert-success alert-dismissible alert-label-icon label-arrow fade show" role="alert">
                    <i class="mdi mdi-check-all label-icon"></i><strong>Success</strong> - {{ session('pesan') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">LPTS</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">QC</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Data Waste</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">


                            <form action="{{ route('data-waste.index') }}" method="GET">
                                <div class="d-flex justify-content-between align-items-end w-100 gap-2">
                                    <div class="d-flex gap-2">
                                        <div class="form-group me-2">
                                            <label for="no_report">No Report</label>
                                            <input type="text" name="no_report" id="no_report" class="form-control"
                                                value="{{ request('no_report') }}" placeholder="Cari No Report">
                                        </div>
                                        <div class="form-group me-2">
                                            <label for="no_so">No SO</label>
                                            <input type="text" name="no_so" id="no_so" class="form-control"
                                                value="{{ request('no_so') }}" placeholder="Cari No SO">
                                        </div>

                                        <div class="align-self-end">
                                            <button type="submit" class="btn btn-primary">Search</button>
                                            <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                                                data-bs-target="#filterModalDataWaste">
                                                <i class="mdi mdi-filter-variant"></i> Filter
                                            </button>
                                            <a href="#" id="exportExcelBtn" class="btn btn-success">Export
                                                Excel</a>
                                            <a href="#" id="printWasteBtn" class="btn btn-success">Print Stock Card
                                                Waste</a>
                                        </div>
                                    </div>
                                    <!-- Tombol kanan sendiri (Add Data) -->
                                    <div>
                                        <button type="button" class="btn btn-info" data-bs-toggle="modal"
                                            data-bs-target="#modalAddReturn">
                                            Add Data
                                        </button>
                                    </div>
                                </div>

                                @push('scripts')
                                    <script>
                                        $(document).ready(function() {
                                            $('#exportExcelBtn').on('click', function(e) {
                                                e.preventDefault();
                                                // Ambil semua parameter filter dari form utama dan modal
                                                var params = {};
                                                // dari form utama
                                                params.no_dn = $('#no_dn').val();
                                                params.product_name = $('#product_name').val();
                                                // dari modal
                                                params.dn_number = $('#filter_dn_number').val();
                                                params.customer_name = $('#filter_customer_name').val();
                                                params.no_po = $('#filter_no_po').val();
                                                params.so_number = $('#filter_so_number').val();
                                                params.date_from = $('#date_from').val();
                                                params.date_to = $('#date_to').val();
                                                // Build query string
                                                var query = $.param(params);
                                                var url = "{{ route('return-customer-ppic.exportExcel') }}?" + query;
                                                window.location.href = url;
                                            });
                                        });
                                    </script>
                                @endpush
                                <!-- Modal Filter Data Waste -->
                                <div class="modal fade" id="filterModalDataWaste" tabindex="-1"
                                    aria-labelledby="filterModalDataWasteLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form method="GET" action="{{ route('data-waste.index') }}">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary text-white">
                                                    <h5 class="modal-title" id="filterModalDataWasteLabel">
                                                        <i class="mdi mdi-filter-variant"></i> Search & Filter
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">

                                                    <div class="mb-3">
                                                        <label for="filter_type_product" class="form-label">Type
                                                            Product</label>
                                                        <input type="text" class="form-control" id="filter_type_product"
                                                            name="type_product" placeholder="Type Product..."
                                                            value="{{ request('type_product') }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="filter_group_sub" class="form-label">Group Sub</label>
                                                        <input type="text" class="form-control" id="filter_group_sub"
                                                            name="group_sub" placeholder="Group Sub..."
                                                            value="{{ request('group_sub') }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="filter_work_center" class="form-label">Work
                                                            Center</label>
                                                        <input type="text" class="form-control"
                                                            id="filter_work_center" name="work_center"
                                                            placeholder="Work Center..."
                                                            value="{{ request('work_center') }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="filter_type_stock" class="form-label">Type
                                                            Stock</label>
                                                        <input type="text" class="form-control" id="filter_type_stock"
                                                            name="type_stock" placeholder="Type Stock..."
                                                            value="{{ request('type_stock') }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="filter_status" class="form-label">Status</label>
                                                        <input type="text" class="form-control" id="filter_status"
                                                            name="status" placeholder="Status..."
                                                            value="{{ request('status') }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Tanggal</label>
                                                        <div class="row g-2">
                                                            <div class="col-md-6">
                                                                <label for="date_from" class="form-label">Dari
                                                                    Tanggal</label>
                                                                <input type="date" class="form-control" id="date_from"
                                                                    name="date_from" value="{{ request('date_from') }}">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="date_to" class="form-label">Sampai
                                                                    Tanggal</label>
                                                                <input type="date" class="form-control" id="date_to"
                                                                    name="date_to" value="{{ request('date_to') }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Filter</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                        </div>
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive " style="overflow-x: auto;">
                            <table id="data-wasteTable" class="table table-bordered table-striped nowrap">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>No Report</th>
                                        <th>No So</th>
                                        <th>Work Center</th>
                                        <th>Weight</th>
                                        <th>Unit</th>
                                        <th>Group Sub</th>
                                        <th>Type Product</th>
                                        <th>Type Stock</th>
                                        <th>Status</th>
                                        <th>Remark</th>
                                        <th>Action</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($datas as $data)
                                        <tr>
                                            <td>{{ $data->waste_date ?? '-' }}</td>
                                            <td>{{ $data->no_report ?? '-' }}</td>
                                            <td>{{ $data->no_so ?? '-' }}</td>
                                            <td>{{ $data->work_center ?? '-' }}</td>
                                            <td>{{ $data->weight ?? '-' }}</td>
                                            <td>{{ $data->unit ?? '-' }}</td>
                                            <td>{{ $data->group_sub ?? '-' }}</td>
                                            <td>{{ $data->type_product ?? '-' }}</td>
                                            <td>{{ $data->type_stock ?? '-' }}</td>
                                            <td>{{ $data->status ?? '-' }}</td>
                                            <td>{{ $data->remark ?? '-' }}</td>
                                            <td>
                                                {{-- <a href="{{ route('return-customer-ppic.print', $data->id) }}"
                                                    class="btn btn-sm btn-primary" target="_blank">
                                                    <i class="mdi mdi-printer"></i>
                                                </a> --}}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Modal Filter Print -->
    <div class="modal fade" id="modalPrintWaste" tabindex="-1" aria-labelledby="modalPrintWasteLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form id="formPrintWaste" method="GET" action="{{ route('data-waste.print') }}" target="_blank">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="modalPrintWasteLabel">
                            <i class="mdi mdi-printer"></i> Print Filter Stock Card Waste
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="print_type_product" class="form-label">Type Product</label>
                            <select class="form-control" id="print_type_product" name="type_product">
                                <option value="">-- All Type Product --</option>
                                @foreach ($typeProducts as $tp)
                                    <option value="{{ $tp }}">{{ $tp }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Waste Date</label>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label for="print_date_from" class="form-label">Dari Tanggal</label>
                                    <input type="date" class="form-control" id="print_date_from" name="date_from">
                                </div>
                                <div class="col-md-6">
                                    <label for="print_date_to" class="form-label">Sampai Tanggal</label>
                                    <input type="date" class="form-control" id="print_date_to" name="date_to">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Print</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- DataTables CSS & JS CDN -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#data-wasteTable').DataTable({});
            $('#printWasteBtn').on('click', function(e) {
                e.preventDefault();
                $('#modalPrintWaste').modal('show');
            });
        });
    </script>
@endpush
