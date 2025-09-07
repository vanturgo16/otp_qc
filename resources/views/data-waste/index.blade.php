@extends('layouts.master')
@section('konten')

    <style>
        .modal-custom {
            max-width: 90% !important;
        }
    </style>
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
                        <h4 class="mb-sm-0 font-size-18">Data Waste</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">QC</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Data Waste</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            {{-- FILTER + ACTIONS --}}
            <div class="row">
                <div class="col-12">

                    {{-- CARD: Stock Waste --}}
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="mdi mdi-database"></i> Stock Waste</h5>
                                <small class="opacity-75">Ringkasan stok per Type Product</small>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive" style="overflow-x: auto;">
                                <table id="stockWasteTable" class="table table-bordered table-striped nowrap">
                                    <thead>
                                        <tr>
                                            <th>Type Product</th>
                                            <th>Stock (kg)</th>
                                            <th>Updated At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($stockWaste as $sw)
                                            <tr>
                                                <td>{{ strtoupper($sw->type_product) }}</td>
                                                <td>{{ number_format((float) $sw->stock, 2) }}</td>
                                                <td>
                                                    {{ $sw->updated_at ? \Carbon\Carbon::parse($sw->updated_at)->format('Y-m-d') : '-' }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center">Belum ada data stok.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

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
                                            <a href="{{ route('data-waste.exportExcel') }}" id="exportExcelBtn"
                                                class="btn btn-success">Export Excel</a>
                                            <a href="#" id="printWasteBtn" class="btn btn-warning">Print Stock
                                                Card
                                                Waste</a>
                                        </div>
                                    </div>

                                    {{-- Kanan: Add Data --}}
                                    <div>
                                        <button type="button" class="btn btn-info" data-bs-toggle="modal"
                                            data-bs-target="#modalAddWaste">
                                            <i class="mdi mdi-plus"></i> Add Data
                                        </button>
                                    </div>
                                </div>

                                @push('scripts')
                                    <script>
                                        $(document).ready(function() {
                                            $('#exportExcelBtn').on('click', function(e) {
                                                e.preventDefault();
                                                var params = {};
                                                params.no_report = $('#no_report').val();
                                                params.no_so = $('#no_so').val();
                                                params.type_product = $('#filter_type_product').val();
                                                params.group_sub = $('#filter_group_sub').val();
                                                params.work_center = $('#filter_work_center').val();
                                                params.type_stock = $('#filter_type_stock').val();
                                                params.status = $('#filter_status').val();
                                                params.date_from = $('#date_from').val();
                                                params.date_to = $('#date_to').val();
                                                var query = $.param(params);
                                                var url = "{{ route('data-waste.exportExcel') }}?" + query;
                                                window.location.href = url;
                                            });
                                        });
                                    </script>
                                @endpush

                                {{-- Modal Filter Data Waste --}}
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
                                                        <label for="filter_group_sub" class="form-label">Group
                                                            Sub</label>
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


                    {{-- TABLE --}}
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

    {{-- MODAL: Print Filter --}}
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
                            <label class="form-label">Type Product</label>
                            <div>
                                @foreach ($typeProducts as $tp)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="type_product[]"
                                            id="tp_{{ $tp }}" value="{{ $tp }}">
                                        <label class="form-check-label"
                                            for="tp_{{ $tp }}">{{ $tp }}</label>
                                    </div>
                                @endforeach
                            </div>
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

    {{-- MODAL: ADD DATA WASTE (UI Only) --}}
    <div class="modal fade" id="modalAddWaste" tabindex="-1" aria-labelledby="modalAddWasteLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-custom">
            <form id="addWasteForm" action="{{ route('data-waste.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title" id="modalAddWasteLabel">
                            <i class="mdi mdi-plus"></i> Add Data Waste
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        {{-- Request Number (preview) --}}
                        <div class="mb-3">
                            <label class="form-label">Request Number</label>
                            <input type="text" class="form-control" value="{{ $report_number }}" readonly>
                            <div class="form-text">Nomor ini akan dipakai saat simpan (bisa berubah jika ada user lain
                                simpan bersamaan).</div>
                        </div>

                        {{-- Date --}}
                        <div class="mb-3">
                            <label for="waste_date_add" class="form-label">Date</label>
                            <input type="date" id="waste_date_add" name="waste_date" class="form-control" required>
                        </div>

                        {{-- Type Product --}}
                        <div class="mb-3">
                            <label for="type_product_add" class="form-label">Type Product</label>
                            <select id="type_product_add" name="type_product" class="form-select col-3" required>
                                <option value="">-- Pilih --</option>
                                <option value="PP">PP</option>
                                <option value="POF">POF</option>
                                <option value="Crosslink">Crosslink</option>
                            </select>
                        </div>

                        {{-- Status --}}
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <input type="text" class="form-control" value="REQUEST" readonly>
                        </div>


                        {{-- Stok Waste (read-only) --}}
                        <div class="mb-3">
                            <label class="form-label">Stok Waste</label>
                            <input type="text" id="stok_waste_view" class="form-control" value="- kg" readonly>
                            <input type="hidden" id="stok_waste_value" value="0"> {{-- buat validasi qty --}}
                        </div>

                        @push('scripts')
                            <script>
                                // Saat type product berubah → isi kolom stok dari objek STOCK_WASTE
                                $(document).on('change', '#type_product_add', function() {
                                    var type = ($(this).val() || '').toUpperCase();
                                    var $view = $('#stok_waste_view');
                                    var $val = $('#stok_waste_value');

                                    if (!type) {
                                        $view.val('- kg');
                                        $val.val('0');
                                        return;
                                    }

                                    // ambil stok dari objek preload; jika tidak ada, 0
                                    var stock = parseFloat(STOCK_WASTE[type] ?? 0);
                                    $view.val(stock + ' kg');
                                    $val.val(stock);
                                });

                                // Validasi ringan: qty <= stok (opsional)
                                $(document).on('submit', '#addWasteForm', function(e) {
                                    var stok = parseFloat($('#stok_waste_value').val() || '0');
                                    var qty = parseFloat($('#qty_take_add').val() || '0');
                                    if (qty > stok) {
                                        e.preventDefault();
                                        alert('Qty Take melebihi Stok Waste.');
                                    }
                                });
                            </script>
                        @endpush



                        {{-- Qty Take --}}
                        <div class="mb-3">
                            <label for="qty_take_add" class="form-label">Qty Take (kg)</label>
                            <input type="number" step="0.01" min="0.01" id="qty_take_add" name="qty_take"
                                class="form-control" required>
                        </div>

                        {{-- Remark --}}
                        <div class="mb-3">
                            <label for="remark_add" class="form-label">Remark</label>
                            <textarea id="remark_add" name="remark" class="form-control" rows="2" placeholder="Catatan (opsional)"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        {{-- tombol submit dinonaktifkan karena UI only --}}
                        <button type="submit" class="btn btn-info">Submit</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
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
        // preload stok dari server (tanpa API call)
        const STOCK_WASTE = @json($stockMap); // contoh: { "PP": 120.5, "POF": 30, "CROSSLINK": 0 }
    </script>

    <script>
        $(document).ready(function() {
            $('#data-wasteTable').DataTable({});

            $('#printWasteBtn').on('click', function(e) {
                e.preventDefault();
                $('#modalPrintWaste').modal('show');
            });

            // Validasi minimal 1 type product untuk print
            $('#formPrintWaste').on('submit', function(e) {
                var checked = $('input[name="type_product[]"]:checked').length;
                if (checked < 1) {
                    alert('Pilih minimal 1 Type Product untuk print!');
                    e.preventDefault();
                    return false;
                }
            });
        });
    </script>
@endpush
