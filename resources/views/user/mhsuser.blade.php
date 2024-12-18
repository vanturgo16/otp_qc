@extends('layouts.dosen.main')

@section('content')
                    <div class="table-container">
                        <div class="t-header"><a href="" class="icon-plus"></a> List User Staff

                        </div>
                        <div class="table-responsive">
                            <table id="copy-print-csv" class="table custom-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Username</th>
                                        <th>Name</th>
                                        <th>Akses Level</th>
                                        <th>Action</th>
                                   
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($musers as $no => $user)
                                    <tr>
                                        <td>{{ ++$no}}</td>
                                        <td>{{ $user->username }}</td>
                                        <td>{{ $user->name }}</td>
                                        
                                        <td>
                                            @if(!empty($user->getRoleNames()))
                                            @foreach($user->getRoleNames() as $role)
                                            <h5><label class="badge badge-primary">{{ $role }}</label></h5>
                                            @endforeach
                                        @endif

                                        </td>
                                        <td>
                                            {{--  @can('users.edit')  --}}
                                            <a href="" class="btn btn-sm btn-info">
                                                <i class="icon-pencil" title="edit"></i>
                                            </a>
                                            <a href="" class="btn btn-sm btn-danger">
                                                <i class="icon-trash" title="Hapus"></i>
                                            </a>

                                            {{--  {{ route('admin.user.edit', $user->id) }}  --}}
                                        {{--  @endcan  --}}
                                        
                                        {{--  @can('users.delete')
                                            <button onClick="Delete(this.id)" class="btn btn-sm btn-danger" id="{{ $user->id }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        @endcan  --}}
                                        </td>
                                    </tr>
                                    @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
             </div>

    @endsection