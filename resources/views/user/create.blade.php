@extends('layouts.dosen.main')

@section('content')

<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="table-container">
                    <div class="t-header"> Form Add Staff

                    </div>
                    <div class="card-body">
                        
                        <form action="/user" method="POST" enctype="multipart/form-data">
                            @csrf
    
                            <div class="form-group">
                                <label>NIP</label>
                                <input type="text" name="username" value="{{ old('username') }}" 
                                placeholder="Masukkan username"
                                class="form-control @error('username') is-invalid @enderror">
    
                                @error('username')
                                <div class="invalid-feedback" style="display: block">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="name" value="{{ old('name') }}" placeholder="Masukkan Nama User"
                                class="form-control @error('name') is-invalid @enderror">
    
                                @error('name')
                                <div class="invalid-feedback" style="display: block">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Departemen</label>
                                <input type="text" name="kode" value="{{ old('kode') }}" placeholder="Masukkan Nama User"
                                class="form-control @error('kode') is-invalid @enderror">
    
                                @error('kode')
                                <div class="invalid-feedback" style="display: block">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            {{-- 
                            <div class="form-group">
                                <label>Utype</label>
                                <select class="form-control select-category @error('utype') is-invalid @enderror" name="utype">
                                  
                                        <option value="ADM">Staff/Dosen</option>
                                        <option value="MHS">Mahasiswa</option>
                                        <option value="BSI3">Administrasi</option>
                                        <option value="BSI6">Dosen Honorer</option> 
                                 
                                </select>
                            </div> --}}
                         
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" value="{{ old('email') }}" placeholder="Masukkan Email"
                                    class="form-control @error('email') is-invalid @enderror">
    
                                @error('email')
                                <div class="invalid-feedback" style="display: block">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
    
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>PASSWORD</label>
                                        <input type="password" name="password" value="{{ old('password') }}" placeholder="Masukkan Password"
                                            class="form-control @error('password') is-invalid @enderror">
            
                                        @error('password')
                                        <div class="invalid-feedback" style="display: block">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>PASSWORD</label>
                                        <input type="password" name="password_confirmation" value="{{ old('password_confirmation') }}" placeholder="Masukkan Konfirmasi Password"
                                            class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">ROLE</label>
                                <br>
                                @foreach ($roles as $role)
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="role[]" value="{{ $role->name }}" id="check-{{ $role->id }}">
                                    <label class="form-check-label" for="check-{{ $role->id }}">
                                        {{ $role->name }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
    
                            <button class="btn btn-primary mr-1 btn-submit" type="submit"><i class="fa fa-paper-plane"></i>
                                SIMPAN</button>
                            <button class="btn btn-warning btn-reset" type="reset"><i class="fa fa-redo"></i> RESET</button>
    
                        </form>
                            
                        </div>
                    </div>
                </div>
            </div>
      

</div>

    </section>


    @endsection