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
                    <h4 class="mb-sm-0 font-size-18"> Detail Barcode</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">PPIC</a></li>
                            <li class="breadcrumb-item active"> Detail Barcode</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">

                    </div>

            <div class="card-body">

                <table id="datatable" class="table table-bordered table-striped dt-responsive  nowrap w-100">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Sales Orders</th>
                        <th>Barcode Number</th>
                        <th>Status </th>

                        <th>Description</th>
                        <th>Work Centers</th>
                        <th>Group</th>
                       
                       
                       
                        <th>Action</th>
                    </tr>
                    </thead>


                    <tbody>
                        @foreach ($barcodeDetails as $data)
                            
                        
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $data->so_number }}</td>
                        <td>{{ $data->barcode_number }}</td>
                        <td>{{ $data->status ?? 'N/A'}}</td>

                        <td><b>{{ $data->product_code }}</b> -{{ $data->description }}</td>
                        <td>{{ $data->work_center }}</td>
                        <td>{{ $data->shift }}</td>
                        
                        
                     
                        <td>

                            <a href="{{ route('print_satuan_standar', $data->barcode_number) }}" class="btn btn-primary waves-effect waves-light">Print Standar</a>
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
