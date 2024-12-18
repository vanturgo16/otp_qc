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
                    <h4 class="mb-sm-0 font-size-18"> Barcode</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">PPIC</a></li>
                            <li class="breadcrumb-item active"> Barcode</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <form action="{{ route('barcode') }}" method="GET" class="d-flex">
                                <div class="form-group me-2">
                                    <label for="start_date">Start Date</label>
                                    <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                                </div>
                                <div class="form-group me-2">
                                    <label for="end_date">End Date</label>
                                    <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                                </div>
                                {{-- <div class="form-group me-2">
                                    <label for="work_center">Work Centers</label>
                                    <select class="form-select" name="work_center" id="work_center">
                                        <option value="">All Work Centers</option>
                                        @foreach($work_centers as $center)
                                            <option value="{{ $center }}" {{ request('work_center') == $center ? 'selected' : '' }}>Barcode {{ $center }}</option>
                                        @endforeach
                                    </select>
                                </div> --}}
                                <div class="form-group align-self-end">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                </div>
                            </form>
                            
                            
                            <div>
                                <a href="/create-barcode" class="btn btn-primary waves-effect waves-light">Add New Generate Barcode</a>
                                
                                <!-- Include modal content -->
                               
                            </div>
                        </div>
                    </div>

            <div class="card-body">

                <table id="datatable" class="table table-bordered dt-responsive nowrap" style="width:100%">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Sales Orders</th>
                        <th>Customers </th>

                        <th>Work Orders</th>
                        <th>Work Centers</th>
                        <th>Group</th>
                        <th>staff</th>
                        <th>Creted_at</th>
                        <th>jml</th>
                        <th>Action</th>
                    </tr>
                    </thead>


                    <tbody>
                        @foreach ($results as $data)
                            
                        
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $data->so_number }}</td>
                        <td>{{ $data->name_cust ?? 'N/A'}}</td>

                        <td>{{ $data->wo_number }}</td>
                        <td>{{ $data->work_center }}</td>
                        <td>{{ $data->shift }}</td>
                        <td>{{ $data->staff }}</td>
                        <td>{{ $data->created_at }}</td>
                        <td><b>{{ $data->barcode_count }}</b></td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-success">Print</button>
                                <button type="button" class="btn btn-success dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="mdi mdi-chevron-down"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('print_standar', $data->id) }}">Print Standar</a>
                                    <a class="dropdown-item" href="{{ route('print_broker', $data->id) }}">Print Broker</a>
                                    <a class="dropdown-item" href="{{ route('print_cbc', $data->id) }}">Print CBC</a>
                                    <hr>
                                    <a class="dropdown-item" href="{{ route('barcode.cange', $data->id) }}">Change SO</a>
                                    <a class="dropdown-item" href="{{ route('table_print') }}">Traceability</a>

                                </div>
                            </div>
                            <a href="{{ route('show_barcode', $data->id) }}" class="btn btn-primary waves-effect waves-light">Detail Barcode</a>
                        </td>
                    </tr>
                    @endforeach
                    
                    <!-- Tambahkan data lainnya di sini -->
                    
                    </tbody>
                </table>

            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->
{{-- <div class="card-body">
    <div class="d-flex flex-wrap gap-3">
    
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Change Sales Orders</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="row mb-4 field-wrapper">
                                <label for="horizontal-password-input" class="col-sm-3 col-form-label">Work Center</label>
                                <div class="col-sm-9">
                                    <select class="form-select request_number data-select2" name="id_work_centers">
                                        <option>SO Number</option>
                                    @foreach ($so as $data)
                                    <option value="{{ $data->id }}" data-id="{{ $data->id }}">{{ $data->so_number }} - {{ $data->so_category }} - {{ $data->status }}</option>
                                    @endforeach
                                    </select>
                                </div>
                            </div>
        
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary">Change</button>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- end preview--> --}}
@endsection
