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
                        <h4 class="mb-sm-0 font-size-18">Return Customer PPIC</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">QC</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Return Customer PPIC</li>
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
                                                    <!-- Date -->
                                                    <div class="col-md-6">
                                                        <label for="tanggal" class="form-label">Date</label>
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
                                                    <!-- Product Name -->
                                                    <div class="col-md-6">
                                                        <label for="name" class="form-label">Product Name</label>
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
                                                    <!-- Weight -->
                                                    <div class="col-md-4">
                                                        <label for="berat" class="form-label">Weight</label>
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
                            <form action="{{ route('return-customer-ppic.index') }}" method="GET">
                                <div class="d-flex justify-content-between align-items-end w-100 gap-2">
                                    <div class="d-flex gap-2">
                                        <div class="form-group me-2">
                                            <label for="no_dn">No DN</label>
                                            <input type="text" name="no_dn" id="no_dn" class="form-control"
                                                value="{{ request('no_dn') }}" placeholder="Cari No DN">
                                        </div>
                                        <div class="form-group me-2">
                                            <label for="product_name">Product Name</label>
                                            <input type="text" name="product_name" id="product_name"
                                                class="form-control" value="{{ request('product_name') }}"
                                                placeholder="Cari Product Name">
                                        </div>

                                        <div class="align-self-end">
                                            <button type="submit" class="btn btn-primary">Search</button>
                                            <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                                                data-bs-target="#filterModalReturn">
                                                <i class="mdi mdi-filter-variant"></i> Filter
                                            </button>
                                            <a href="#" id="exportExcelBtn" class="btn btn-success">Export
                                                Excel</a>
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
                                <!-- Modal Filter Return Customer -->
                                <div class="modal fade" id="filterModalReturn" tabindex="-1"
                                    aria-labelledby="filterModalReturnLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form method="GET" action="{{ route('return-customer-ppic.index') }}">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary text-white">
                                                    <h5 class="modal-title" id="filterModalReturnLabel">
                                                        <i class="mdi mdi-filter-variant"></i> Search & Filter
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="filter_dn_number" class="form-label">No DN</label>
                                                        <input type="text" class="form-control" id="filter_dn_number"
                                                            name="dn_number" placeholder="No DN..."
                                                            value="{{ request('dn_number') }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="filter_customer_name"
                                                            class="form-label">Customer</label>
                                                        <input type="text" class="form-control"
                                                            id="filter_customer_name" name="customer_name"
                                                            placeholder="Customer Name..."
                                                            value="{{ request('customer_name') }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="filter_no_po" class="form-label">No PO</label>
                                                        <input type="text" class="form-control" id="filter_no_po"
                                                            name="no_po" placeholder="No PO..."
                                                            value="{{ request('no_po') }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="filter_so_number" class="form-label">No SO</label>
                                                        <input type="text" class="form-control" id="filter_so_number"
                                                            name="so_number" placeholder="No SO..."
                                                            value="{{ request('so_number') }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Date Return</label>
                                                        <div class="row g-2">
                                                            <div class="col-md-6">
                                                                <label for="date_from" class="form-label">From
                                                                    Date</label>
                                                                <input type="date" class="form-control" id="date_from"
                                                                    name="date_from" value="{{ request('date_from') }}">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="date_to" class="form-label">To
                                                                    Date</label>
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
                                        <th>Date</th>
                                        <th>No DN</th>
                                        <th>Customer</th>
                                        <th>No PO</th>
                                        <th>No SO</th>
                                        <th>Product Name</th>
                                        <th>Qty</th>
                                        <th>Unit</th>
                                        <th>Weight</th>
                                        <th>QC Status</th>
                                        <th>Keterangan</th>
                                        <th>Action</th>


                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($returns as $return)
                                        <tr>
                                            <td>{{ $return->date_return }}</td>
                                            <td>{{ $return->dn_number }}</td>
                                            <td>{{ $return->customer_name }}</td>
                                            <td>{{ $return->no_po }}</td>
                                            <td>{{ $return->so_number }}</td>
                                            <td>{{ $return->name }}</td>
                                            <td>{{ $return->qty }}</td>
                                            <td>{{ $return->unit }}</td>
                                            <td>{{ $return->weight }}</td>
                                            <td>{{ $return->qc_status }}</td>
                                            <td>{{ $return->keterangan }}</td>


                                            <td>
                                                <a href="{{ route('return-customer-ppic.print', encrypt($return->id_delivery_note_details)) }}"
                                                    class="btn btn-primary" target="_blank">Print PDF</a>

                                                @if ($return->qc_status !== 'scrap' && $return->qc_status !== 'rework')
                                                    <!-- Tombol Scrap buka modal -->
                                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                        data-bs-target="#modalScrap{{ $return->id }}">
                                                        Scrap
                                                    </button>
                                                    <!-- Tombol Rework buka modal -->
                                                    <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                                        data-bs-target="#modalRework{{ $return->id }}">
                                                        Rework
                                                    </button>

                                                    <!-- Modal Scrap -->
                                                    <div class="modal fade" id="modalScrap{{ $return->id }}"
                                                        tabindex="-1"
                                                        aria-labelledby="modalScrapLabel{{ $return->id }}"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <form
                                                                action="{{ route('return-customer-ppic.scrap', $return->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title"
                                                                            id="modalScrapLabel{{ $return->id }}">
                                                                            Scrap Data Return Customer
                                                                        </h5>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <label for="waste_date{{ $return->id }}">Date
                                                                            Scrap</label>
                                                                        <input type="date" name="waste_date"
                                                                            id="waste_date{{ $return->id }}"
                                                                            class="form-control"
                                                                            value="{{ now()->format('Y-m-d') }}" required>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="submit"
                                                                            class="btn btn-danger">Scrap</button>
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-bs-dismiss="modal">Batal</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>

                                                    <!-- Modal Rework -->
                                                    <div class="modal fade" id="modalRework{{ $return->id }}"
                                                        tabindex="-1"
                                                        aria-labelledby="modalReworkLabel{{ $return->id }}"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <form
                                                                action="{{ route('return-customer-ppic.rework', $return->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title"
                                                                            id="modalReworkLabel{{ $return->id }}">
                                                                            Rework Data Return Customer
                                                                        </h5>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <label for="rework_date{{ $return->id }}">Date
                                                                            Rework</label>
                                                                        <input type="date" name="rework_date"
                                                                            id="rework_date{{ $return->id }}"
                                                                            class="form-control"
                                                                            value="{{ now()->format('Y-m-d') }}" required>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="submit"
                                                                            class="btn btn-warning">Rework</button>
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-bs-dismiss="modal">Batal</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @endif
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
