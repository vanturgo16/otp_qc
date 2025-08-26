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

                            <!-- Modal Add Data -->
                            <div class="modal fade" id="modalAddReturn" tabindex="-1" aria-labelledby="modalAddReturnLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <form action="{{ route('return-customer-ppic.store') }}" method="POST">
                                        @csrf
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalAddReturnLabel">Add Data Return Customer
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row g-3">
                                                    <!-- No DN -->
                                                    <div class="col-md-6">
                                                        <label for="id_delivery_note_details" class="form-label">No
                                                            DN</label>
                                                        <select name="id_delivery_note_details"
                                                            id="id_delivery_note_details" class="form-select" required>
                                                            <option value="">-- Pilih DN --</option>
                                                            @foreach ($dn_details as $dn)
                                                                <option value="{{ $dn->id_dn_details }}"
                                                                    data-id_delivery_notes="{{ $dn->id_delivery_notes }}"
                                                                    data-customer="{{ $dn->id_master_customers }}"
                                                                    data-customer_name="{{ $dn->customer_name }}"
                                                                    data-po="{{ $dn->no_po }}"
                                                                    data-so="{{ $dn->id_sales_orders }}"
                                                                    data-so_number="{{ $dn->so_number }}"
                                                                    data-product="{{ $dn->product_name }}"
                                                                    data-id_unit="{{ $dn->id_master_units }}"
                                                                    data-unit="{{ $dn->unit }}">
                                                                    {{ $dn->dn_number }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('id_delivery_note_details')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <!-- Tanggal -->
                                                    <div class="col-md-6">
                                                        <label for="tanggal" class="form-label">Tanggal</label>
                                                        <input type="date" name="tanggal" id="tanggal"
                                                            class="form-control" required>
                                                        @error('tanggal')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <!-- Customer -->
                                                    <div class="col-md-6">
                                                        <label for="id_master_customers" class="form-label">Customer</label>
                                                        <input type="text" name="customer_name" id="customer_name"
                                                            class="form-control" readonly>
                                                        <input type="hidden" name="id_master_customers"
                                                            id="id_master_customers">
                                                        @error('id_master_customers')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <!-- No PO -->
                                                    <div class="col-md-6">
                                                        <label for="no_po" class="form-label">No PO</label>
                                                        <input type="text" name="no_po" id="no_po"
                                                            class="form-control" readonly>
                                                        @error('no_po')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <!-- No SO -->
                                                    <div class="col-md-6">
                                                        <label for="id_sales_orders" class="form-label">No SO</label>
                                                        <input type="text" name="so_number" id="so_number"
                                                            class="form-control" readonly>
                                                        <input type="hidden" name="id_sales_orders" id="id_sales_orders">
                                                        @error('id_sales_orders')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <!-- Nama Produk -->
                                                    <div class="col-md-6">
                                                        <label for="name" class="form-label">Nama
                                                            Produk</label>
                                                        <input type="text" name="name" id="name"
                                                            class="form-control" readonly>
                                                        @error('name')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <!-- Jumlah -->
                                                    <div class="col-md-4">
                                                        <label for="qty" class="form-label">Qty</label>
                                                        <input type="number" name="qty" id="qty"
                                                            class="form-control" required step="0.001" min="0">
                                                        @error('qty')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <!-- Unit -->
                                                    <div class="col-md-4">
                                                        <label for="id_master_units" class="form-label">Unit</label>
                                                        <input type="text" name="unit" id="unit"
                                                            class="form-control" readonly>
                                                        <input type="hidden" name="id_master_units"
                                                            id="id_master_units">
                                                        @error('id_master_units')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <!-- Berat -->
                                                    <div class="col-md-4">
                                                        <label for="berat" class="form-label">Berat</label>
                                                        <input type="number" name="berat" id="berat"
                                                            class="form-control" step="1" min="0">
                                                        @error('berat')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <!-- Keterangan -->
                                                    <div class="col-md-12">
                                                        <label for="keterangan" class="form-label">Keterangan</label>
                                                        <textarea name="keterangan" id="keterangan" class="form-control" rows="2"></textarea>
                                                        @error('keterangan')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <!-- id_delivery_notes (hidden) -->
                                                    <input type="hidden" name="id_delivery_notes"
                                                        id="id_delivery_notes">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Batal</button>
                                            </div>
                                        </div>
                                    </form>
                                    @push('scripts')
                                        <script>
                                            $('#id_delivery_note_details').on('change', function() {
                                                var selected = $(this).find('option:selected');
                                                $('#id_delivery_notes').val(selected.data('id_delivery_notes') || '');
                                                $('#id_master_customers').val(selected.data('customer') || '');
                                                $('#customer_name').val(selected.data('customer_name') || '');
                                                $('#no_po').val(selected.data('po') || '');
                                                $('#id_sales_orders').val(selected.data('so') || '');
                                                $('#so_number').val(selected.data('so_number') || '');
                                                $('#name').val(selected.data('product') || '');
                                                $('#id_master_units').val(selected.data('id_unit') || '');
                                                $('#unit').val(selected.data('unit') || '');
                                            });
                                        </script>
                                    @endpush
                                </div>
                            </div>
                            <form action="{{ route('return-customer-ppic.index') }}" method="GET" class="d-flex">
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

                                    <!-- Tombol Add Data -->
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                        data-bs-target="#modalAddReturn">
                                        Add Data
                                    </button>


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
                                                params.no_lpts = $('#no_lpts').val();
                                                params.type_product = $('#type_product').val();
                                                // dari modal
                                                params.packing_number = $('#filter_report').val();
                                                params.barcode_number = $('#filter_barcode').val();
                                                params.group_sub_name = $('#filter_group_sub').val();
                                                params.thickness = $('#filter_thickness').val();
                                                params.date_from = $('#date_from').val();
                                                params.date_to = $('#date_to').val();
                                                // Build query string
                                                var query = $.param(params);
                                                var url = "{{ route('lpts.exportExcel') }}?" + query;
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
                    <div class="card-body">
                        <div class="table-responsive " style="overflow-x: auto;">
                            <table id="lptsTable" class="table table-bordered table-striped nowrap">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>No DN</th>
                                        <th>Customer</th>
                                        <th>No PO</th>
                                        <th>No SO</th>
                                        <th>Nama Produk</th>
                                        <th>Qty</th>
                                        <th>Unit</th>
                                        <th>Berat</th>
                                        <th>Keterangan</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($returns as $return)
                                        <tr>
                                            <td>{{ $return->tanggal }}</td>
                                            <td>{{ $return->dn_number }}</td>
                                            <td>{{ $return->customer_name }}</td>
                                            <td>{{ $return->no_po }}</td>
                                            <td>{{ $return->so_number }}</td>
                                            <td>{{ $return->name }}</td>
                                            <td>{{ $return->qty }}</td>
                                            <td>{{ $return->unit }}</td>
                                            <td>{{ $return->berat }}</td>
                                            <td>{{ $return->keterangan }}</td>
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
