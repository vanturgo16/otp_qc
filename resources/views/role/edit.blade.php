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
                    <h4 class="mb-sm-0 font-size-18">Edit Role Access</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">PPIC</a></li>
                            <li class="breadcrumb-item active">Edit Role Access</li>
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
                                <a href="{{ url('/role/create') }}" class="btn btn-primary waves-effect waves-light">Back to Role</a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <form action="/role/update/{{ $role->id }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('patch')
                            <div class="form-group">
                                <label for="name">Nama Role</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $role->name) }}" placeholder="Masukkan Nama Role"
                                    class="form-control @error('name') is-invalid @enderror">
                                @error('name')
                                <div class="invalid-feedback" style="display: block">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            @php
                                $permissionsGroups = [
                                    'Akunting', 'Warehouse', 'Marketing', 'Purchasing', 'Configuration', 'PPIC', 'Produksi'
                                ];
                                $groupedPermissions = $permissions->groupBy(function($permission) use ($permissionsGroups) {
                                    foreach ($permissionsGroups as $group) {
                                        if (Str::contains($permission->name, $group)) {
                                            return $group;
                                        }
                                    }
                                    return 'Other';
                                });
                            @endphp

                            <div class="table-responsive">
                                <br>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Permission Group</th>
                                            <th>Permissions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($permissionsGroups as $group)
                                            @if ($groupedPermissions->has($group))
                                                <tr>
                                                    <td>{{ $group }} Permissions</td>
                                                    <td>
                                                        @foreach ($groupedPermissions[$group] as $permission)
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox" 
                                                                    name="permissions[]" 
                                                                    value="{{ $permission->name }}" 
                                                                    id="check-{{ $permission->id }}"
                                                                    @if ($role->permissions->contains($permission)) checked @endif>
                                                                <label class="form-check-label" for="check-{{ $permission->id }}">
                                                                    {{ $permission->name }}
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <br>
                            <div>
                                <button class="btn btn-primary mr-1 btn-submit" type="submit"><i class="fa fa-paper-plane"></i> Update</button>
                                <button class="btn btn-warning btn-reset" type="reset"><i class="fa fa-redo"></i> Reset</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
