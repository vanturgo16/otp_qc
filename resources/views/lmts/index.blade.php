@extends('layouts.master')
@section('konten')
@php
use Illuminate\Support\Facades\DB;
@endphp
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
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible alert-label-icon label-arrow fade show" role="alert">
                    <i class="mdi mdi-alert-circle label-icon"></i><strong>Error</strong> - {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">LMTS</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">QC</a></li>
                                <li class="breadcrumb-item active" aria-current="page">LMTS</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">


                            <form action="{{ route('lmts.index') }}" method="GET">
                                <div class="d-flex justify-content-start align-items-end w-100 gap-2">
                                    <div class="d-flex gap-2">
                                        <div class="form-group me-2">
                                            <label for="no_lmts">No LMTS</label>
                                            <input type="text" name="no_lmts" id="no_lmts" class="form-control"
                                                value="{{ request('no_lmts') }}" placeholder="Cari No LMTS">
                                        </div>

                                        <div class="align-self-end">
                                            <button type="submit" class="btn btn-primary">Search</button>
                                            <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                                                data-bs-target="#filterModalLmts">
                                                <i class="mdi mdi-filter-variant"></i> Filter
                                            </button>
                                            <a href="{{ route('lmts.export.excel', request()->all()) }}" class="btn btn-success">
                                                <i class="mdi mdi-file-excel"></i> Export Excel
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Filter LMTS -->
                                <div class="modal fade" id="filterModalLmts" tabindex="-1"
                                    aria-labelledby="filterModalLmtsLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form method="GET" action="{{ route('lmts.index') }}">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary text-white">
                                                    <h5 class="modal-title" id="filterModalLmtsLabel">
                                                        <i class="mdi mdi-filter-variant"></i> Search & Filter LMTS
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="filter_receipt_number" class="form-label">GRN</label>
                                                        <input type="text" class="form-control" id="filter_receipt_number"
                                                            name="receipt_number" placeholder="Receipt Number..."
                                                            value="{{ request('receipt_number') }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="filter_lot_number" class="form-label">Lot Number</label>
                                                        <input type="text" class="form-control" id="filter_lot_number"
                                                            name="lot_number" placeholder="Lot Number..."
                                                            value="{{ request('lot_number') }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="filter_description" class="form-label">Description</label>
                                                        <input type="text" class="form-control" id="filter_description"
                                                            name="description" placeholder="Description..."
                                                            value="{{ request('description') }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="filter_type_product" class="form-label">Type Product</label>
                                                        <input type="text" class="form-control" id="filter_type_product"
                                                            name="type_product" placeholder="Type Product..."
                                                            value="{{ request('type_product') }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Date Range</label>
                                                        <div class="row g-2">
                                                            <div class="col-md-6">
                                                                <label for="date_from" class="form-label">From Date</label>
                                                                <input type="date" class="form-control" id="date_from"
                                                                    name="date_from" value="{{ request('date_from') }}">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="date_to" class="form-label">To Date</label>
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
                            <table id="lmtsTable" class="table table-bordered table-striped nowrap">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>No LMTS</th>
                                        <th>GRN</th>
                                        <th>Lot Number</th>
                                        <th>External Lot</th>
                                        <th>Product</th>
                                        <th>Date</th>
                                        <th>Total GLQ</th>
                                        <th>Unit</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Remark</th>
                                        <th>Action</th>


                                    </tr>
                                </thead>
                                <!-- filepath: e:\Projek_Qc\otp_qc\resources\views\return_customers_ppic\index.blade.php -->
                                <tbody>
                                    @foreach ($datas as $data)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $data->no_lmts }}</td>
                                            <td>{{ $data->receipt_number }}</td>
                                            <td>{{ $data->lot_number }}</td>
                                            <td>{{ $data->external_lot }}</td>
                                            <td>{{ $data->description }}</td>
                                            <td>{{ $data->date }}</td>
                                            <td>{{ $data->total_glq}}</td>
                                            <td>{{ $data->unit }}</td>
                                            <td>{{ $data->type_product }}</td>
                                            <td>
                                                @php
                                                    $statusText = '';
                                                    $statusClass = '';
                                                    switch($data->status) {
                                                        case 0:
                                                            $statusText = 'Hold';
                                                            $statusClass = 'bg-primary';
                                                            break;
                                                        case 1:
                                                            $statusText = 'Scrap';
                                                            $statusClass = 'bg-danger';
                                                            break;
                                                        case 2:
                                                            $statusText = 'Return';
                                                            $statusClass = 'bg-info';
                                                            break;
                                                        case 3:
                                                            $statusText = 'Repair';
                                                            $statusClass = 'bg-warning';
                                                            break;
                                                        default:
                                                            $statusText = 'Unknown';
                                                            $statusClass = 'bg-secondary';
                                                    }
                                                @endphp
                                                <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                                            </td>
                                            <td>{{ $data->remarks  }}</td>
                                            <td>
                                                @php
                                                    $buttonActive = json_decode($data->button_active ?? '{}', true);
                                                    $isReturn = $buttonActive['is_return'] ?? 0;
                                                    $isRepair = $buttonActive['is_repair'] ?? 0;
                                                    $isScrap = $buttonActive['is_scrap'] ?? 0;
                                                @endphp

                                                @if ($data->status != 0)
                                                    <a href=""
                                                        class="btn btn-primary btn-sm" target="_blank">Print PDF</a>
                                                @endif

                                                {{-- Jika status masih 0 (Hold), tampilkan action buttons --}}
                                                @if ($data->status == 0)
                                                    @if ($isScrap == 1)
                                                        <!-- Tombol Scrap -->
                                                        <button type="button" class="btn btn-danger btn-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalScrap{{ $loop->iteration }}">
                                                            Scrap
                                                        </button>
                                                    @endif

                                                    @if ($isRepair == 1)
                                                        <!-- Tombol Repair/Rework -->
                                                        <button type="button" class="btn btn-warning btn-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalRework{{ $loop->iteration }}">
                                                            Repair
                                                        </button>
                                                    @endif

                                                    @if ($isReturn == 1)
                                                        <!-- Tombol Return -->
                                                        <button type="button" class="btn btn-info btn-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalReturn{{ $loop->iteration }}">
                                                            Return
                                                        </button>
                                                    @endif

                                                    @if ($isScrap == 0 && $isRepair == 0 && $isReturn == 0)
                                                        <span class="badge bg-secondary text-white">No Action Available</span>
                                                    @endif

                                                    {{-- Button Unposted hanya muncul jika status = 0 (Hold) dan user Super Admin --}}
                                                    @if (auth()->user()->role == 'Super Admin' || auth()->user()->role == 'super admin' || auth()->user()->role == 'SUPER ADMIN')
                                                        <!-- Tombol Unposted (hanya untuk Super Admin) -->
                                                        <button type="button" class="btn btn-dark btn-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalUnposted{{ $loop->iteration }}">
                                                            Unposted
                                                        </button>
                                                    @endif
                                                @else
                                                    {{-- Jika sudah diproses (status != 0), tampilkan badge sesuai status --}}
                                                    @switch($data->status)
                                                        @case(1)
                                                            <span class="badge bg-danger text-white">
                                                                <i class="mdi mdi-delete"></i> Scrapped
                                                            </span>
                                                            @break
                                                        @case(2)
                                                            <span class="badge bg-info text-white">
                                                                <i class="mdi mdi-keyboard-return"></i> Returned
                                                            </span>
                                                            @break
                                                        @case(3)
                                                            <span class="badge bg-warning text-dark">
                                                                <i class="mdi mdi-wrench"></i> Repaired
                                                            </span>
                                                            @break
                                                        @default
                                                            <span class="badge bg-success text-white">
                                                                <i class="mdi mdi-check"></i> Processed
                                                            </span>
                                                    @endswitch
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

    <!-- Modals untuk Action Buttons -->
    @foreach ($datas as $data)
        @php
            $buttonActive = json_decode($data->button_active ?? '{}', true);
            $isReturn = $buttonActive['is_return'] ?? 0;
            $isRepair = $buttonActive['is_repair'] ?? 0;
            $isScrap = $buttonActive['is_scrap'] ?? 0;
        @endphp

        @if ($isScrap == 1)
            <!-- Modal Scrap -->
            <div class="modal fade" id="modalScrap{{ $loop->iteration }}" tabindex="-1"
                aria-labelledby="modalScrapLabel{{ $loop->iteration }}" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('lmts.scrap', $data->id) }}" method="POST">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title" id="modalScrapLabel{{ $loop->iteration }}">
                                    <i class="mdi mdi-delete"></i> Scrap Data LMTS
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-warning py-2">
                                    <i class="mdi mdi-alert-outline"></i>
                                    <strong>Peringatan:</strong> Proses scrap akan mengurangi stock dan mengubah status LMTS.
                                </div>

                                <div class="row g-2 mb-3">
                                    <div class="col-md-6">
                                        <small class="text-muted">No LMTS</small>
                                        <div class="fw-bold">{{ $data->no_lmts }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted">Lot Number</small>
                                        <div class="fw-bold">{{ $data->lot_number }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted">Qty Scrap</small>
                                        <div class="fw-bold text-danger">{{ $data->qty }}</div>
                                    </div>
                                    @if($data->id_master_products)
                                        @php
                                            $masterProduct = DB::table('master_product_fgs')->where('id', $data->id_master_products)->first();
                                            $currentStock = $masterProduct->stock ?? 0;
                                        @endphp
                                        <div class="col-md-6">
                                            <small class="text-muted">Stock Tersedia</small>
                                            <div>
                                                <span class="badge {{ $currentStock >= $data->qty ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $currentStock }}
                                                </span>
                                            </div>
                                        </div>
                                        @if($currentStock < $data->qty)
                                            <div class="col-12">
                                                <div class="alert alert-danger py-2 mt-2">
                                                    <i class="mdi mdi-alert-circle"></i>
                                                    <strong>Stock tidak mencukupi!</strong>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                </div>

                                <div class="col g-2">
                                    <div>
                                        <label for="scrap_date{{ $loop->iteration }}">Tanggal Scrap <span class="text-danger">*</span></label>
                                        <input type="date" name="scrap_date" id="scrap_date{{ $loop->iteration }}"
                                            class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                                    </div>
                                    <div>
                                        <label for="lmts_notes{{ $loop->iteration }}">Catatan <span class="text-danger">*</span></label>
                                        <textarea name="lmts_notes" id="lmts_notes{{ $loop->iteration }}"
                                            class="form-control" rows="2"
                                            placeholder="Alasan scrap..." required></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                @if($data->id_master_products)
                                    @php
                                        $masterProduct = DB::table('master_product_fgs')->where('id', $data->id_master_products)->first();
                                        $currentStock = $masterProduct->stock ?? 0;
                                        $canScrap = $currentStock >= $data->qty;
                                    @endphp
                                    <button type="submit" class="btn btn-danger" {{ !$canScrap ? 'disabled' : '' }}>
                                        <i class="mdi mdi-delete"></i>
                                        {{ $canScrap ? 'Proses Scrap' : 'Stock Tidak Cukup' }}
                                    </button>
                                @else
                                    <button type="submit" class="btn btn-danger">
                                        <i class="mdi mdi-delete"></i> Proses Scrap
                                    </button>
                                @endif
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        @if ($isRepair == 1)
            <!-- Modal Repair -->
            <div class="modal fade" id="modalRework{{ $loop->iteration }}" tabindex="-1"
                aria-labelledby="modalReworkLabel{{ $loop->iteration }}" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="#" method="POST">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalReworkLabel{{ $loop->iteration }}">
                                    Repair Data LMTS
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row g-2 mb-3">
                                    <div class="col-md-6">
                                        <small class="text-muted">No LMTS</small>
                                        <div class="fw-bold">{{ $data->no_lmts }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted">Lot Number</small>
                                        <div class="fw-bold">{{ $data->lot_number }}</div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="repair_date{{ $loop->iteration }}">Tanggal Repair</label>
                                    <input type="date" name="repair_date" id="repair_date{{ $loop->iteration }}"
                                        class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-warning">Repair</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        @if ($isReturn == 1)
            <!-- Modal Return -->
            <div class="modal fade" id="modalReturn{{ $loop->iteration }}" tabindex="-1"
                aria-labelledby="modalReturnLabel{{ $loop->iteration }}" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('lmts.return', $data->id) }}" method="POST">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header bg-info text-white">
                                <h5 class="modal-title" id="modalReturnLabel{{ $loop->iteration }}">
                                    <i class="mdi mdi-keyboard-return"></i> Return Data LMTS
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-info py-2">
                                    <i class="mdi mdi-information-outline"></i>
                                    <strong>Informasi:</strong> Proses return akan mengembalikan barang ke supplier, mengurangi stock dan mengubah status LMTS.
                                </div>
                                
                                <div class="alert alert-warning py-2">
                                    <i class="mdi mdi-truck-delivery"></i>
                                    <strong>Catatan:</strong> Barang yang di-return akan dikembalikan ke supplier dan stock akan berkurang dari inventory.
                                </div>

                                <div class="row g-2 mb-3">
                                    <div class="col-md-6">
                                        <small class="text-muted">No LMTS</small>
                                        <div class="fw-bold">{{ $data->no_lmts }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted">Lot Number</small>
                                        <div class="fw-bold">{{ $data->lot_number }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted">Qty Return</small>
                                        <div class="fw-bold text-info">{{ $data->qty }}</div>
                                    </div>
                                    @if($data->id_master_products)
                                        @php
                                            $masterProduct = DB::table('master_product_fgs')->where('id', $data->id_master_products)->first();
                                            $currentStock = $masterProduct->stock ?? 0;
                                        @endphp
                                        <div class="col-md-6">
                                            <small class="text-muted">Stock Tersedia</small>
                                            <div>
                                                <span class="badge {{ $currentStock >= $data->qty ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $currentStock }}
                                                </span>
                                            </div>
                                        </div>
                                        @if($currentStock < $data->qty)
                                            <div class="col-12">
                                                <div class="alert alert-danger py-2 mt-2">
                                                    <i class="mdi mdi-alert-circle"></i>
                                                    <strong>Stock tidak mencukupi!</strong>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                </div>

                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <label for="return_date{{ $loop->iteration }}">Tanggal Return <span class="text-danger">*</span></label>
                                        <input type="date" name="return_date" id="return_date{{ $loop->iteration }}"
                                            class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="return_reason{{ $loop->iteration }}">Alasan Return ke Supplier <span class="text-danger">*</span></label>
                                        <textarea name="return_reason" id="return_reason{{ $loop->iteration }}"
                                            class="form-control" rows="2" placeholder="Masukkan alasan mengapa barang dikembalikan ke supplier..." required></textarea>
                                        <small class="text-muted">Contoh: Kualitas tidak sesuai, defect, expired, dll.</small>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                @if($data->id_master_products)
                                    @php
                                        $masterProduct = DB::table('master_product_fgs')->where('id', $data->id_master_products)->first();
                                        $currentStock = $masterProduct->stock ?? 0;
                                        $canReturn = $currentStock >= $data->qty;
                                    @endphp
                                    <button type="submit" class="btn btn-info" {{ !$canReturn ? 'disabled' : '' }}>
                                        <i class="mdi mdi-keyboard-return"></i>
                                        {{ $canReturn ? 'Proses Return' : 'Stock Tidak Cukup' }}
                                    </button>
                                @else
                                    <button type="submit" class="btn btn-info">
                                        <i class="mdi mdi-keyboard-return"></i> Proses Return
                                    </button>
                                @endif
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    @endforeach

    <!-- Modal Unposted untuk Super Admin -->
    @if (auth()->user()->role == 'Super Admin' || auth()->user()->role == 'super admin' || auth()->user()->role == 'SUPER ADMIN')
        @foreach ($datas as $data)
            <div class="modal fade" id="modalUnposted{{ $loop->iteration }}" tabindex="-1"
                aria-labelledby="modalUnpostedLabel{{ $loop->iteration }}" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('lmts.unposted', $data->id) }}" method="POST">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalUnpostedLabel{{ $loop->iteration }}">
                                    Unposted Data LMTS
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-danger">
                                    <i class="mdi mdi-alert-circle-outline"></i>
                                    <strong>PERINGATAN KERAS!</strong> Anda akan menghapus data LMTS ini secara permanen dari database.
                                </div>
                                <div class="alert alert-info">
                                    <i class="mdi mdi-information-outline"></i>
                                    <strong>Informasi:</strong> Data yang sudah dihapus tidak dapat dikembalikan!
                                </div>

                                <div class="border p-3 mb-3 bg-light">
                                    <h6 class="text-danger mb-2">Data yang akan dihapus:</h6>
                                    <p><strong>No LMTS:</strong> {{ $data->no_lmts }}</p>
                                    <p><strong>Lot Number:</strong> {{ $data->lot_number }}</p>
                                    <p><strong>Description:</strong> {{ $data->description }}</p>
                                    <p><strong>Current Status:</strong> {{ $data->status }}</p>
                                </div>

                                <label for="unpost_reason{{ $loop->iteration }}">Alasan Penghapusan <span class="text-danger">*</span></label>
                                <textarea name="unpost_reason" id="unpost_reason{{ $loop->iteration }}"
                                    class="form-control" rows="3" placeholder="Masukkan alasan mengapa data ini dihapus..." required></textarea>

                                <div class="form-check mt-3">
                                    <input class="form-check-input" type="checkbox" id="confirmDelete{{ $loop->iteration }}" required>
                                    <label class="form-check-label text-danger" for="confirmDelete{{ $loop->iteration }}">
                                        <strong>Saya memahami bahwa data ini akan dihapus permanen</strong>
                                    </label>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-danger" id="btnDelete{{ $loop->iteration }}" disabled>
                                    <i class="mdi mdi-delete"></i> Hapus Data
                                </button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            </div>

                            <script>
                                document.getElementById('confirmDelete{{ $loop->iteration }}').addEventListener('change', function() {
                                    document.getElementById('btnDelete{{ $loop->iteration }}').disabled = !this.checked;
                                });
                            </script>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    @endif
    </div>
@endsection

@push('scripts')
    <!-- DataTables CSS & JS CDN -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#lmtsTable').DataTable({});
        });
    </script>
@endpush
