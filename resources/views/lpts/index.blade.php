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
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">LPTS</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">QC</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Data Sample</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <form action="{{ route('lpts.index') }}" method="GET" class="d-flex">
                                <div class="form-group me-2">
                                    <label for="no_lpts">No LPTS</label>
                                    <input type="text" name="no_lpts" id="no_lpts" class="form-control"
                                        value="{{ request('no_lpts') }}" placeholder="Cari No LPTS">
                                </div>
                                <div class="form-group me-2">
                                    <label for="type_product">Type Product</label>
                                    <input type="text" name="type_product" id="type_product" class="form-control"
                                        value="{{ request('type_product') }}" placeholder="Cari Type Product">
                                </div>
                                <div class="form-group align-self-end d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                    <button type="button" class="btn btn-secondary " data-bs-toggle="modal"
                                        data-bs-target="#filterModalLpts">
                                        <i class="mdi mdi-filter-variant"></i> Filter
                                    </button>
                                    <a href="#" id="exportExcelBtn" class="btn btn-success">Export Excel</a>
                                </div>
                                @push('scripts')
                                    <script>
                                        $(document).ready(function() {
                                            $('#exportExcelBtn').on('click', function(e) {
                                                e.preventDefault();
                                                // Ambil semua parameter filter dari form utama dan modal
                                                var params = {};
                                                // dari form utama
                                                params.no_sample = $('#no_sample').val();
                                                params.sample_type = $('#sample_type').val();
                                                // dari modal (jika sudah pernah diisi)
                                                params.so_number = $('#filter_so_number').val();
                                                params.customer = $('#filter_customer').val();
                                                params.barcode = $('#filter_barcode').val();
                                                params.marketing = $('#filter_marketing').val();
                                                // Build query string
                                                var query = $.param(params);
                                                var url = "{{ route('sample.exportExcel') }}?" + query;
                                                window.location.href = url;
                                            });
                                        });
                                    </script>
                                @endpush
                                <!-- Modal Filter LPTS -->
                                <div class="modal fade" id="filterModalLpts" tabindex="-1"
                                    aria-labelledby="filterModalLptsLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form method="GET" action="{{ route('lpts.index') }}">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary text-white">
                                                    <h5 class="modal-title" id="filterModalLptsLabel">
                                                        <i class="mdi mdi-filter-variant"></i> Search & Filter
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="filter_report" class="form-label">Report</label>
                                                        <input type="text" class="form-control" id="filter_report"
                                                            name="packing_number" placeholder="Report Number..."
                                                            value="{{ request('packing_number') }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="filter_barcode" class="form-label">Barcode</label>
                                                        <input type="text" class="form-control" id="filter_barcode"
                                                            name="barcode_number" placeholder="Barcode..."
                                                            value="{{ request('barcode_number') }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="filter_group_sub" class="form-label">Grup Sub</label>
                                                        <input type="text" class="form-control" id="filter_group_sub"
                                                            name="group_sub_name" placeholder="Group Sub..."
                                                            value="{{ request('group_sub_name') }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="filter_thickness" class="form-label">Tiknes</label>
                                                        <input type="text" class="form-control" id="filter_thickness"
                                                            name="thickness" placeholder="Thickness..."
                                                            value="{{ request('thickness') }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Date Report</label>
                                                        <div class="row g-2">
                                                            <div class="col-md-6">
                                                                <label for="date_from" class="form-label">Tanggal
                                                                    Dari</label>
                                                                <input type="date" class="form-control" id="date_from"
                                                                    name="date_from" value="{{ request('date_from') }}">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="date_to" class="form-label">Sampai
                                                                    Dari</label>
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
                    <div class="">
                        <div class="table-responsive " style="overflow-x: auto;">
                            <table id="lptsTable" class="table table-bordered table-striped nowrap">
                                <thead>
                                    <tr>
                                        <th>No LPTS</th>
                                        <th>Lot/Report/Packing Number</th>
                                        <th>Barcode</th>
                                        <th>Wo Number</th>
                                        <th>Operator</th>
                                        <th>Description</th>
                                        <th>Thickness</th>
                                        <th>Type Product</th>
                                        <th>Group Sub</th>
                                        <th>QTY</th>
                                        <th>Unit</th>
                                        <th>Date Report</th>
                                        <th>Status</th>
                                        <th>Keterangan</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($datas as $data)
                                        <tr>
                                            <td>{{ $data->no_lpts ?? '-' }}</td>
                                            <td>{{ $data->packing_number ?? '-' }}</td>
                                            <td>{{ $data->barcode_number ?? '-' }}</td>
                                            <td>{{ $data->wo_number ?? '-' }}</td>
                                            <td>{{ $data->staff ?? '-' }}</td>
                                            <td>{{ $data->description ?? '-' }}</td>
                                            <td>{{ $data->thickness ?? '-' }}</td>
                                            <td>{{ $data->type_product ?? '-' }}</td>
                                            <td>{{ $data->group_sub_name ?? '-' }}</td>
                                            <td>{{ $data->qty ?? '-' }}</td>
                                            <td>{{ $data->unit ?? '-' }}</td>
                                            <td>{{ $data->created_at ?? '-' }}</td>
                                            <td>{{ $data->status ?? '-' }}</td>
                                            <td><!-- Tombol buka modal -->
                                                <button type="button" class="btn btn-sm btn-success"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalKeterangan{{ $data->work_order_id }}">
                                                    Add Keterangan
                                                </button>
                                                <!-- Modal keterangan -->
                                                <div class="modal fade" id="modalKeterangan{{ $data->work_order_id }}"
                                                    tabindex="-1" aria-labelledby="modalLabel{{ $data->work_order_id }}"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <form action="{{ route('lpts.keterangan') }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="no_lpts"
                                                                value="{{ $data->no_lpts }}">
                                                            <input type="hidden" name="id_wo"
                                                                value="{{ $data->work_order_id }}">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"
                                                                        id="modalLabel{{ $data->work_order_id }}">Tambah
                                                                        Keterangan LPTS</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <textarea name="keterangan" class="form-control" rows="4" required></textarea>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit"
                                                                        class="btn btn-primary">Simpan</button>
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Batal</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="" class="btn btn-sm btn-primary" target="_blank">Print
                                                    PDF</a>
                                                <a href="" class="btn btn-sm btn-warning">Edit</a>
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
@endsection

@push('scripts')
    <!-- DataTables CSS & JS CDN -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#lptsTable').DataTable({});
        });
    </script>
@endpush
