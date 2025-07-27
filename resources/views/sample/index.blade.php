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
                    <h4 class="mb-sm-0 font-size-18">Data Sample</h4>
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
                        <form action="{{ route('sample.index') }}" method="GET" class="d-flex">
                            <div class="form-group me-2">
                                <label for="no_sample">No Sample</label>
                                <input type="text" name="no_sample" id="no_sample" class="form-control" value="{{ request('no_sample') }}" placeholder="Cari No Sample">
                            </div>
                            <div class="form-group me-2">
                                <label for="sample_type">Sample Type</label>
                                <input type="text" name="sample_type" id="sample_type" class="form-control" value="{{ request('sample_type') }}" placeholder="Cari Sample Type">
                            </div>
                            <div class="form-group align-self-end d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Search</button>
                                <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#filterModal">Filter</button>
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
                    <!-- Filter Modal -->
                    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="filterModalLabel">Advanced Filter</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form method="GET" action="{{ route('sample.index') }}">
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="filter_so_number" class="form-label">SO Number</label>
                                            <input type="text" class="form-control" id="filter_so_number" name="so_number" value="{{ request('so_number') }}" placeholder="Search SO Number">
                                        </div>
                                        <div class="mb-3">
                                            <label for="filter_customer" class="form-label">Customer</label>
                                            <input type="text" class="form-control" id="filter_customer" name="customer" value="{{ request('customer') }}" placeholder="Search Customer">
                                        </div>
                                        <div class="mb-3">
                                            <label for="filter_barcode" class="form-label">Barcode</label>
                                            <input type="text" class="form-control" id="filter_barcode" name="barcode" value="{{ request('barcode') }}" placeholder="Search Barcode">
                                        </div>
                                        <div class="mb-3">
                                            <label for="filter_marketing" class="form-label">Marketing</label>
                                            <input type="text" class="form-control" id="filter_marketing" name="marketing" value="{{ request('marketing') }}" placeholder="Search Marketing">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Apply Filter</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="sampleTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No SO</th>
                                        <th>No Sample</th>
                                        <th>Request Date</th>
                                        <th>Done Date</th>
                                        <th>Submission Date</th>
                                        <th>Done Duration</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dataSoSamples as $data)
                                    <tr>
                                        <td>{{ $data->so_number }}</td>
                                        <td>{{ $data->no_sample }}</td>
                                        <td>{{ $data->request_date }}</td>
                                        <td>{{ $data->sample_done_date }}</td>
                                        <td>{{ $data->sample_submission_date }}</td>
                                        <td>{{ $data->done_duration }}</td>
                                        <td>
                                            @if ($data->sample_done_date == null && $data->sample_submission_date == null)
                                                <span class="badge bg-info">Progress</span>
                                            @elseif($data->sample_done_date != null && $data->sample_submission_date == null)
                                                <span class="badge bg-warning">Open</span>
                                            @elseif($data->sample_done_date != null && $data->sample_submission_date != null)
                                                <span class="badge bg-success">Closed</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#detail{{ $data->id_so }}">Detail</button>
                                            @if ($data->sample_done_date == null || $data->sample_submission_date == null)
                                                <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#edit{{ $data->id_so }}">Edit</button>
                                            @endif
                                            @if ($data->sample_done_date != null && $data->sample_submission_date != null)
                                                <a href="{{ route('sample.printPdf', $data->id_so) }}" class="btn btn-info btn-sm" target="_blank">Print PDF</a>
                                            @endif
                                        </td>
                                    </tr>
                                    {{-- Detail Modal --}}
                                    <div class="modal fade" id="detail{{ $data->id_so }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-top" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="staticBackdropLabel">Detail Info</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                    <div class="modal-body">
                                                        <div class="row text-start mb-2">
                                                            <div class="col-3"><label for="">No. SO</label></div>
                                                            <div class="col-1"><label for="">:</label></div>
                                                            <div class="col-8">{{ $data->so_number }}</div>
                                                        </div>
                                                        <div class="row text-start mb-2">
                                                            <div class="col-3"><label for="">No. Sample</label></div>
                                                            <div class="col-1"><label for="">:</label></div>
                                                            <div class="col-8">{{ $data->no_sample }}</div>
                                                        </div>
                                                        <div class="row text-start mb-2">
                                                            <div class="col-3"><label for="">Request Date:</label></div>
                                                            <div class="col-1"><label for="">:</label></div>
                                                            <div class="col-8">{{ $data->request_date }}</div>
                                                        </div>
                                                        <div class="row text-start mb-2">
                                                            <div class="col-3"><label for="">Customer:</label></div>
                                                            <div class="col-1"><label for="">:</label></div>
                                                            <div class="col-8">{{ $data->customer_name }}</div>
                                                        </div>
                                                        <div class="row text-start mb-2">
                                                            <div class="col-3"><label for="">Marketing:</label></div>
                                                            <div class="col-1"><label for="">:</label></div>
                                                            <div class="col-8">{{ $data->sales_name }}</div>
                                                        </div>
                                                        <div class="row text-start mb-2">
                                                            <div class="col-3"><label for="">Product/Item:</label></div>
                                                            <div class="col-1"><label for="">:</label></div>
                                                            <div class="col-8">{{ $data->product_item }}</div>
                                                        </div>
                                                        <div class="row text-start mb-2">
                                                            <div class="col-3"><label for="">Sample Type:</label></div>
                                                            <div class="col-1"><label for="">:</label></div>
                                                            <div class="col-8">{{ $data->type }}</div>
                                                        </div>
                                                        <div class="row text-start mb-2">
                                                            <div class="col-3"><label for="">Perforasi:</label></div>
                                                            <div class="col-1"><label for="">:</label></div>
                                                            <div class="col-8">{{ $data->perforasi }}</div>
                                                        </div>
                                                        <div class="row text-start mb-2">
                                                            <div class="col-3"><label for="">QTY:</label></div>
                                                            <div class="col-1"><label for="">:</label></div>
                                                            <div class="col-8">{{ $data->qty }}</div>
                                                        </div>
                                                        <div class="row text-start mb-2">
                                                            <div class="col-3"><label for="">Unit:</label></div>
                                                            <div class="col-1"><label for="">:</label></div>
                                                            <div class="col-8">{{ $data->unit }}</div>
                                                        </div>
                                                        <div class="row text-start mb-2">
                                                            <div class="col-3"><label for="">Lot/Barcode:</label></div>
                                                            <div class="col-1"><label for="">:</label></div>
                                                            <div class="col-8">{{ $data->all_barcodes }}</div>
                                                        </div>
                                                        <div class="row text-start mb-2">
                                                            <div class="col-3"><label for="">Weight:</label></div>
                                                            <div class="col-1"><label for="">:</label></div>
                                                            <div class="col-8">{{ $data->weight }}</div>
                                                        </div>
                                                        <div class="row text-start mb-2">
                                                            <div class="col-3"><label for="">Type Product:</label></div>
                                                            <div class="col-1"><label for="">:</label></div>
                                                            <div class="col-8">{{ $data->type_product }}</div>
                                                        </div>
                                                        <div class="row text-start mb-2">
                                                            <div class="col-3"><label for="">Done Date:</label></div>
                                                            <div class="col-1"><label for="">:</label></div>
                                                            <div class="col-8">{{ $data->sample_done_date }}</div>
                                                        </div>
                                                        <div class="row text-start mb-2">
                                                            <div class="col-3"><label for="">Submission Date:</label></div>
                                                            <div class="col-1"><label for="">:</label></div>
                                                            <div class="col-8">{{ $data->sample_submission_date }}</div>
                                                        </div>
                                                        @if ($data->done_duration)
                                                        <div class="row text-start mb-2">
                                                            <div class="col-3"><label for="">Created Date:</label></div>
                                                            <div class="col-1"><label for="">:</label></div>
                                                            <div class="col-8">{{ date('Y-m-d', strtotime($data->created_at)) }}</div>
                                                        </div>
                                                        @endif
                                                        <div class="row text-start mb-2">
                                                            <div class="col-3"><label for="">Done Duration:</label></div>
                                                            <div class="col-1"><label for="">:</label></div>
                                                            <div class="col-8">{{ $data->done_duration . " Days" }}</div>
                                                        </div>
                                                        <div class="row text-start mb-2">
                                                            <div class="col-3"><label for="">Remarks:</label></div>
                                                            <div class="col-1"><label for="">:</label></div>
                                                            <div class="col-8">{{ $data->remarks }}</div>
                                                        </div>
                                                        <div class="row text-start mb-2">
                                                            <div class="col-3"><label for="">Status:</label></div>
                                                            <div class="col-1"><label for="">:</label></div>
                                                            <div class="col-8">
                                                                @if ($data->sample_done_date == null && $data->sample_submission_date == null)
                                                                    <span class="badge bg-info">Progress</span>
                                                                @elseif($data->sample_done_date != null && $data->sample_submission_date == null)
                                                                    <span class="badge bg-warning">Open</span>
                                                                 @elseif($data->sample_done_date != null && $data->sample_submission_date != null)
                                                                    <span class="badge bg-success">Closed</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Detail Modal --}}

                                    {{-- Edit Modal --}}
                                    <div class="modal fade" id="edit{{ $data->id_so }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-top" role="document">
                                            <div class="modal-content">
                                                <form action="{{ route('sample.update', $data->id_so) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="staticBackdropLabel">Edit Data</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row text-start mb-2">
                                                        <div class="col-3"><label for="">No. SO</label></div>
                                                        <div class="col-1"><label for="">:</label></div>
                                                        <div class="col-8">{{ $data->so_number }}</div>
                                                    </div>
                                                    <div class="row text-start mb-2">
                                                        <div class="col-3"><label for="">No. Sample</label></div>
                                                        <div class="col-1"><label for="">:</label></div>
                                                        @if ($data->no_sample)
                                                            <div class="col-8">{{ $data->no_sample }}</div>
                                                        @else
                                                            <div class="col-8"><input type="text" class="form-control" name="no_sample" id="no_sample" value="{{ $no . "/PS/" . $formattedDate }}" @error('no_sample') is-invalid @enderror readonly></div>
                                                            @error('no_sample')
                                                            <div class="invalid-feedback" style="display: block">
                                                                {{ $message }}
                                                            </div>
                                                            @enderror
                                                        @endif
                                                    </div>
                                                    <div class="row text-start mb-2">
                                                        <div class="col-3"><label for="">Request Date:</label></div>
                                                        <div class="col-1"><label for="">:</label></div>
                                                        <div class="col-8">{{ $data->request_date }}</div>
                                                    </div>
                                                    <div class="row text-start mb-2">
                                                        <div class="col-3"><label for="">Customer:</label></div>
                                                        <div class="col-1"><label for="">:</label></div>
                                                        <div class="col-8">{{ $data->customer_name }}</div>
                                                    </div>
                                                    <div class="row text-start mb-2">
                                                        <div class="col-3"><label for="">Marketing:</label></div>
                                                        <div class="col-1"><label for="">:</label></div>
                                                        <div class="col-8">{{ $data->sales_name }}</div>
                                                    </div>
                                                    <div class="row text-start mb-2">
                                                        <div class="col-3"><label for="">Product/Item:</label></div>
                                                        <div class="col-1"><label for="">:</label></div>
                                                        <div class="col-8">{{ $data->product_item }}</div>
                                                    </div>
                                                    <div class="row text-start mb-2">
                                                        <div class="col-3"><label for="">Sample Type:</label></div>
                                                        <div class="col-1"><label for="">:</label></div>
                                                        <div class="col-8">{{ $data->type }}</div>
                                                    </div>
                                                    <div class="row text-start mb-2">
                                                        <div class="col-3"><label for="">Perforasi:</label></div>
                                                        <div class="col-1"><label for="">:</label></div>
                                                        <div class="col-8">{{ $data->perforasi }}</div>
                                                    </div>
                                                    <div class="row text-start mb-2">
                                                        <div class="col-3"><label for="">QTY:</label></div>
                                                        <div class="col-1"><label for="">:</label></div>
                                                        <div class="col-8">{{ $data->qty }}</div>
                                                    </div>
                                                    <div class="row text-start mb-2">
                                                        <div class="col-3"><label for="">Unit:</label></div>
                                                        <div class="col-1"><label for="">:</label></div>
                                                        <div class="col-8">{{ $data->unit }}</div>
                                                    </div>
                                                    <div class="row text-start mb-2">
                                                        <div class="col-3"><label for="">Lot/Barcode:</label></div>
                                                        <div class="col-1"><label for="">:</label></div>
                                                        <div class="col-8">{{ $data->all_barcodes }}</div>
                                                    </div>
                                                    <div class="row text-start mb-2">
                                                        <div class="col-3"><label for="">Weight:</label></div>
                                                        <div class="col-1"><label for="">:</label></div>
                                                        <div class="col-8">{{ $data->weight }}</div>
                                                    </div>
                                                    <div class="row text-start mb-2">
                                                        <div class="col-3"><label for="">Type Product:</label></div>
                                                        <div class="col-1"><label for="">:</label></div>
                                                        <div class="col-8">{{ $data->type_product }}</div>
                                                    </div>
                                                    <div class="row text-start mb-2">
                                                        <div class="col-3"><label for="">Done Date:</label></div>
                                                        <div class="col-1"><label for="">:</label></div>
                                                        @if ($data->sample_done_date)
                                                            <div class="col-8">{{ $data->sample_done_date }}</div>
                                                        @else
                                                            <div class="col-8"><input type="date" class="form-control" name="done_date" id="done_date" value=""></div>
                                                        @endif
                                                    </div>
                                                    <div class="row text-start mb-2">
                                                        <div class="col-3"><label for="">Submission Date:</label></div>
                                                        <div class="col-1"><label for="">:</label></div>
                                                        @if ($data->sample_submission_date)
                                                            <div class="col-8">{{ $data->sample_submission_date }}</div>
                                                        @else
                                                            <div class="col-8"><input type="date" class="form-control" name="submission_date" id="submission_date" value="" @error('submission_date') is-invalid @enderror></div>
                                                            @error('submission_date')
                                                            <div class="invalid-feedback" style="display: block">
                                                                {{ $message }}
                                                            </div>
                                                            @enderror
                                                        @endif
                                                    </div>
                                                    @if ($data->done_duration)
                                                    <div class="row text-start mb-2">
                                                        <div class="col-3"><label for="">Created Date:</label></div>
                                                        <div class="col-1"><label for="">:</label></div>
                                                        <div class="col-8">{{ date('Y-m-d', strtotime($data->created_at)) }}</div>
                                                    </div>
                                                    @endif
                                                    <div class="row text-start mb-2">
                                                        <div class="col-3"><label for="">Done Duration:</label></div>
                                                        <div class="col-1"><label for="">:</label></div>
                                                        <div class="col-8">{{ $data->done_duration . " Days" }}</div>
                                                    </div>
                                                    <div class="row text-start mb-2">
                                                        <div class="col-3"><label for="">Remarks:</label></div>
                                                        <div class="col-1"><label for="">:</label></div>
                                                        @if ($data->remarks)
                                                            <div class="col-8">{{ $data->remarks }}</div>
                                                        @else
                                                            <div class="col-8"><input type="text" class="form-control" name="remarks" id="remarks" value="" placeholder="Input Remarks ..."></div>
                                                        @endif
                                                    </div>
                                                    <div class="row text-start mb-2">
                                                        <div class="col-3"><label for="">Status:</label></div>
                                                        <div class="col-1"><label for="">:</label></div>
                                                        <div class="col-8">
                                                            @if ($data->sample_done_date == null && $data->sample_submission_date == null)
                                                                <span class="badge bg-info">Progress</span>
                                                            @elseif($data->sample_done_date != null && $data->sample_submission_date == null)
                                                                <span class="badge bg-warning">Open</span>
                                                                @elseif($data->sample_done_date != null && $data->sample_submission_date != null)
                                                                <span class="badge bg-success">Closed</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-success">Update</button>
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Edit Modal --}}
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
        $('#sampleTable').DataTable({
        });
    });
</script>
@endpush
