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
                    <h4 class="mb-sm-0 font-size-18"> Certificate of Analysis</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">QC</a></li>
                            <li class="breadcrumb-item active"> Certificate of Analysis</li>
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
                            <form action="{{ route('coa') }}" method="GET" class="d-flex">
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
                        <th>No COA</th>
                        <th>Customers </th>
                        <th>No KO</th>
                        <th>Brand Name</th>
                       
                        <th>Action</th>
                    </tr>
                    </thead>


                    <tbody>
                        @foreach ($query as $data)
                            
                        
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td></td>
                        <td>{{ $data->customer }}</td>
                        <td>{{ $data->id_order_confirmations ?? 'N/A'}}</td>

                        <td>{{ $data->description }}</td>

                        <td>
                            @php
                             $id = Crypt::encryptString($data->id_order_confirmations);
                             @endphp
                            <div class="btn-group">
                                {{-- <button type="button" class="btn btn-success">Print</button> --}}

                            <a href="{{ url('show-coa', $id) }}" class="btn btn-success">show</a>
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
