@extends('layouts.master')

@section('konten')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Dashboard</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible alert-label-icon label-arrow fade show" role="alert">
                    <i class="mdi mdi-check-all label-icon"></i><strong>Success</strong> - {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Welcome Section -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="text-center py-3">
                                <h5>Welcome to the "Dashboard Quality Control"</h5>
                                <p class="text-muted">Here you can Manage Quality Control on the system PT Olefina Tifaplas
                                    Polikemindo</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Confirmation (KO) Section -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card border-0" style="background-color: #f0f0f0;">
                        <div class="card-body py-6">
                            <h6 class="mb-0 text-dark">Laporan Produk Tidak Sesuai (LPTS)</h6>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <!-- Request / Un Post -->
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm" style="min-height: 120px;">
                        <div class="card-body">
                            <div class="mb-2">
                                <small class="text-muted">Request / Un Post</small>
                            </div>
                            <h2 class="mb-2 fw-bold" style="color: #333;">1</h2>
                            <div>
                                <span class="badge" style="background-color: #00d4aa; color: white; font-size: 11px;">
                                    +0 Hari Ini
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Posted -->
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm" style="min-height: 120px;">
                        <div class="card-body">
                            <div class="mb-2">
                                <small class="text-muted">Posted</small>
                            </div>
                            <h2 class="mb-2 fw-bold" style="color: #17a2b8;">97</h2>
                            <div>
                                <span class="badge" style="background-color: #00d4aa; color: white; font-size: 11px;">
                                    +5 Hari Ini
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Closed -->
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm" style="min-height: 120px;">
                        <div class="card-body">
                            <div class="mb-2">
                                <small class="text-muted">Closed</small>
                            </div>
                            <h2 class="mb-2 fw-bold" style="color: #28a745;">0</h2>
                            <div>
                                <span class="badge" style="background-color: #00d4aa; color: white; font-size: 11px;">
                                    +0 Hari Ini
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total -->
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm" style="min-height: 120px;">
                        <div class="card-body">
                            <div class="mb-2">
                                <small class="text-muted">Total</small>
                            </div>
                            <h2 class="mb-2 fw-bold" style="color: #333;">98</h2>
                            <div>
                                <span class="badge" style="background-color: #00d4aa; color: white; font-size: 11px;">
                                    +5 Hari Ini
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sales Order (SO) Section -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card border-0" style="background-color: #f0f0f0;">
                        <div class="card-body py-6">
                            <h6 class="mb-0 text-dark">Laporan Material Tidak Sesuai (LMTS)</h6>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <!-- Request / Un Post -->
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm" style="min-height: 120px;">
                        <div class="card-body">
                            <div class="mb-2">
                                <small class="text-muted">Request / Un Post</small>
                            </div>
                            <h2 class="mb-2 fw-bold" style="color: #333;">3</h2>
                            <div>
                                <span class="badge" style="background-color: #00d4aa; color: white; font-size: 11px;">
                                    +1 Hari Ini
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Posted -->
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm" style="min-height: 120px;">
                        <div class="card-body">
                            <div class="mb-2">
                                <small class="text-muted">Posted</small>
                            </div>
                            <h2 class="mb-2 fw-bold" style="color: #17a2b8;">396</h2>
                            <div>
                                <span class="badge" style="background-color: #17a2b8; color: white; font-size: 11px;">
                                    +12 Hari Ini
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Closed -->
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm" style="min-height: 120px;">
                        <div class="card-body">
                            <div class="mb-2">
                                <small class="text-muted">Closed</small>
                            </div>
                            <h2 class="mb-2 fw-bold" style="color: #28a745;">1689</h2>
                            <div>
                                <span class="badge" style="background-color: #00d4aa; color: white; font-size: 11px;">
                                    +8 Hari Ini
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total -->
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm" style="min-height: 120px;">
                        <div class="card-body">
                            <div class="mb-2">
                                <small class="text-muted">Total</small>
                            </div>
                            <h2 class="mb-2 fw-bold" style="color: #333;">2088</h2>
                            <div>
                                <span class="badge" style="background-color: #17a2b8; color: white; font-size: 11px;">
                                    +21 Hari Ini
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Comment untuk LMTS & LPTS Section (akan dibuat nanti) --}}
            {{-- 
        <!-- LMTS Section -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card border-0" style="background-color: #f0f0f0;">
                    <div class="card-body py-2">
                        <h6 class="mb-0 text-dark">LMTS (Laporan Material Testing & Stock)</h6>
                    </div>
                </div>
            </div>
        </div>

        <!-- LPTS Section -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card border-0" style="background-color: #f0f0f0;">
                    <div class="card-body py-2">
                        <h6 class="mb-0 text-dark">LPTS (Laporan Produk Testing & Stock)</h6>
                    </div>
                </div>
            </div>
        </div>
        --}}

        </div>
    </div>
@endsection
