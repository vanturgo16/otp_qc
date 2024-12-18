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
                    <h4 class="mb-sm-0 font-size-18"> User Access </h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">PPIC</a></li>
                            <li class="breadcrumb-item active"> User Access </li>
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
                                <a href="{{url('/dashboard') }}" class="btn btn-primary waves-effect waves-light"> Back to dashboard</a>
                                
                                <!-- Include modal content -->
                               
                            </div>
                        </div>
                    </div>

            <div class="card-body">

                <table id="datatable" class="table table-bordered table-striped dt-responsive  nowrap w-100">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th><center>Departemen</center></th>
                            <th>email</th>
                            <th>Akses Level</th>
                            <th>Action</th>
                       
                        </tr>
                    </thead>


                    <tbody>
                        @foreach ($users as $no => $user)
                        <tr>
                            <td>{{ ++$no}}</td>
                            <td>{{ $user->name }}</td>
                            <td><center>{{ $user->nama_dev }} </center></td>
                            <td>{{ $user->email }} </td>
                            
                            <td>
                              
                                @if ($user->getRoleNames()->isNotEmpty())
                                <span class="badge bg-danger rounded-pill" style="font-size: 1em;">
                                    {{ implode(', ', $user->getRoleNames()->toArray()) }}
                                </span>
                            @endif
                            
                            

                            </td>
                            <td>
                                 {{-- @can('users.edit')  --}}
                                <a href="/user/edit/{{ $user->id }}" class="btn btn-sm btn-info">
                                    {{-- <i class="icon-pencil" title="edit"></i> --}} Edit
                                </a>
                              

                                 {{-- {{ route('admin.user.edit', $user->id) }}   --}}
                             {{-- @endcan  --}}
                            
                            {{-- @can('users.delete') --}}
                            <form name="form1" id="form1" action="/hapus-user/{{$user->id}}" method="post" class="d-inline">
                                @method('delete')
                                @csrf
                                <button type="submit" class="btn btn-sm btn-secondary">Delete
                                    {{-- <i class="icon-trash" title="Hapus"></i> --}}
                                </button>
                              </form>
                            {{-- @endcan --}}

                            
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
