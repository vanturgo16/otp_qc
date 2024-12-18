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
                    <h4 class="mb-sm-0 font-size-18"> List Role</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">PPIC</a></li>
                            <li class="breadcrumb-item active"> List Role</li>
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

                            <div>
                                <a href="{{ url('/role/create') }}" class="btn btn-primary waves-effect waves-light">Add New Role</a>
                                
                                <!-- Include modal content -->
                               
                            </div>
                        </div>
                    </div>

            <div class="card-body">

                <table id="copy-print-csv" class="table custom-table">
                    <thead>
                        <tr>
                            <th scope="col" style="text-align: center; width: 6%">No.</th>
                            <th scope="col" style="width: 15%">Nama Role</th>
                            <th scope="col">Permissions</th>
                            <th scope="col">Ujian Permissions</th>
                            <th scope="col" style="text-align: center; width: 15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $no => $role)
                        <tr>
                            <td style="text-align: center">{{ $loop->iteration }}</td>
                            <td>{{ $role->name }}</td>
                            <td>
                                @foreach($role->getPermissionNames() as $permission)
                                @if(!Str::contains($permission, '_ujian'))
                                <button class="btn btn-sm btn-info mb-1 mt-1 mr-1">{{ $permission }}</button>
                                @endif
                                @endforeach
                            </td>
                            <td>
                                @foreach($role->getPermissionNames() as $permission)
                                @if(Str::contains($permission, '_ujian'))
                                <button class="btn btn-sm btn-warning mb-1 mt-1 mr-1">{{ $permission }}</button>
                                @endif
                                @endforeach
                            </td>
                            <td class="text-center">
                                @can('PPIC_role.edit') 
                                <a href="/role/edit/{{ $role->id }}" class="btn btn-sm btn-primary">Edit
                                    {{-- <i class="icon-pencil"></i> --}}
                                </a>
                                @endcan 
                                @can('PPIC_role.delete') 
                                <button onClick="Delete(this.id)" class="btn btn-sm btn-danger" id="{{ $role->id }}">
                                    {{-- <i class="icon-trash"></i>  --}}Delete
                                </button>
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

@endsection
