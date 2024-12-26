@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">List History Stock</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Master Data</a></li>
                            <li class="breadcrumb-item active">History Stock</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#rm" role="tab" id="rmBtn">
                                    <span class="d-block d-sm-none"><i class="fas fa-history"></i></span>
                                    <span class="d-none d-sm-block">Raw Material</span>    
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#wip" role="tab" id="wipBtn">
                                    <span class="d-block d-sm-none"><i class="far fa-history"></i></span>
                                    <span class="d-none d-sm-block">WIP</span>    
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#fg" role="tab" id="fgBtn">
                                    <span class="d-block d-sm-none"><i class="far fa-history"></i></span>
                                    <span class="d-none d-sm-block">Finish Good</span>    
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#ta" role="tab" id="taBtn">
                                    <span class="d-block d-sm-none"><i class="fas fa-history"></i></span>
                                    <span class="d-none d-sm-block">Sparepart & Aux</span>    
                                </a>
                            </li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content p-3 text-muted">
                            <div class="tab-pane active" id="rm" role="tabpanel">
                                <!-- Table -->
                                <table class="table table-bordered table-striped table-hover dt-responsive nowrap w-100" id="ssTableRM" style="font-size: small">
                                    <thead>
                                        <tr>
                                            <th class="align-middle text-center">#</th>
                                            <th class="align-middle text-center">Code / Description</th>
                                            <th class="align-middle text-center">Stock Saat Ini</th>
                                            <th class="align-middle text-center">Datang</th>
                                            <th class="align-middle text-center">Pakai</th>
                                            <th class="align-middle text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($results['RM'] as $data)
                                        <tr>
                                            <td class="align-middle text-center">{{ $loop->iteration }}</td>
                                            <td class="align-middle text-center"><b>{{ $data->code }}</b> <br>{{ $data->description }}</td>
                                            <td class="align-middle text-center">{{ $data->stock }}</td>
                                            <td class="align-middle text-center">{{ $data->total_in }}</td>
                                            <td class="align-middle text-center">{{ $data->total_out }}</td>
                                            <td class="align-middle text-center">
                                                <a href="#" class="btn btn-info"><i class="dripicons-checkmark"></i> History Stock</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                

                            </div>
                            <div class="tab-pane" id="wip" role="tabpanel">
                                <!-- Table -->
                                <table class="table table-bordered table-striped table-hover dt-responsive nowrap w-100" id="ssTableWIP" style="font-size: small">
                                    <thead>
                                        <tr>
                                            <th class="align-middle text-center">#</th>
                                            <th class="align-middle text-center">WIP Code</th>
                                            <th class="align-middle text-center">Description</th>
                                            <th class="align-middle text-center">Stock</th>
                                            <th class="align-middle text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($results['WIP'] as $data)
                                        <tr>
                                            <td class="align-middle text-center">{{ $loop->iteration }}</td>
                                            <td class="align-middle text-center"><b>{{ $data->code }}</b></td>
                                            <td class="align-middle text-center">{{ $data->description }}<br><b>perforasi-{{ $data->perforasi }}</b></td>
                                            <td class="align-middle text-center">{{ $data->stock }}</td>
                                            <td class="align-middle text-center">
                                                <a href="#" class="btn btn-info"><i class="dripicons-checkmark"></i> History Stock</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                
                                <!-- Server Side -->

                            </div>
                            <div class="tab-pane" id="fg" role="tabpanel">
                                <!-- Table -->
                                <table class="table table-bordered table-striped table-hover dt-responsive nowrap w-100" id="ssTableFG" style="font-size: small">
                                    <thead>
                                        <tr>
                                            <th class="align-middle text-center">#</th>
                                            <th class="align-middle text-center">Product Code</th>
                                            <th class="align-middle text-center">Description</th>
                                            <th class="align-middle text-center">Stock</th>
                                            <th class="align-middle text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($results['FG'] as $data)
                                        <tr>
                                            <td class="align-middle text-center">{{ $loop->iteration }}</td>
                                            <td class="align-middle text-center"><b>{{ $data->code }}</b></td>
                                            <td class="align-middle text-center">{{ $data->description }} <br><b>perforasi-{{ $data->perforasi }}</b></td>
                                            <td class="align-middle text-center">{{ $data->stock }}</td>
                                            <td class="align-middle text-center">
                                                <a href="#" class="btn btn-info"><i class="dripicons-checkmark"></i> History Stock</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                
                                <!-- Server Side -->

                            </div>
                            <div class="tab-pane" id="ta" role="tabpanel">
                                <!-- Table -->
                                <table class="table table-bordered table-striped table-hover dt-responsive nowrap w-100" id="ssTableTA" style="font-size: small">
                                    <thead>
                                        <tr>
                                            <th class="align-middle text-center">#</th>
                                            <th class="align-middle text-center">Code / Description</th>
                                            <th class="align-middle text-center">Stock Saat Ini</th>
                                            <th class="align-middle text-center">Datang</th>
                                            <th class="align-middle text-center">Pakai</th>
                                            <th class="align-middle text-center">Department</th>
                                            <th class="align-middle text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($results['TA'] as $data)
                                        <tr>
                                            <td class="align-middle text-center">{{ $loop->iteration }}</td>
                                            <td class="align-middle text-center"><b>{{ $data->code }}</b> <br>{{ $data->description }}</td>
                                            <td class="align-middle text-center">{{ $data->stock }}</td>
                                            <td class="align-middle text-center">{{ $data->total_in }}</td>
                                            <td class="align-middle text-center">{{ $data->total_out }}</td>
                                            <td class="align-middle text-center">{{ $data->departement_name }}</td>
                                            <td class="align-middle text-center">
                                                <a href="#" class="btn btn-info"><i class="dripicons-checkmark"></i> History Stock</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                
                                <!-- Server Side -->

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection