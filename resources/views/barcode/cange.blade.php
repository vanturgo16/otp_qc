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
        <div class="card-body">

            <table id="datatable" class="table table-bordered table-striped dt-responsive  nowrap w-100">
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

                                    <a class="dropdown-item" href="{{ route('table_print') }}">Traceability</a>

                                </div>
                            </div>

                        </td>
                    </tr>
                    @endforeach

                    <!-- Tambahkan data lainnya di sini -->

                </tbody>
            </table>

        </div>
        <form method="post" action="/store-barcode" class="form-material m-t-40" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Generate Barcode</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">PPIC</a></li>
                                <li class="breadcrumb-item active">Generate Barcode</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Generate Barcode</h4>
                        </div>
                        <div class="card-body p-4">
                            <div class="col-sm-12">
                                <div class="mt-4 mt-lg-0">



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


                                    <div class="row left-content-end">
                                        <div class="col-sm-9">
                                            <div>
                                                <a href="/barcode" class="btn btn-info waves-effect waves-light">Back</a>
                                                <button type="submit" class="btn btn-primary w-md" name="save">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <!-- end row -->
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

@endsection