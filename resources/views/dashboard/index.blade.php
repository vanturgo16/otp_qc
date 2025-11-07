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

            <!-- LPTS Section -->
            <div class="row ">
                <div class="col-12">
                    <div class="card border-0" style="background-color: #f0f0f0;">
                        <div class="card-body py-3">
                            <h6 class="mb-0 text-dark">
                                <i class="mdi mdi-clipboard-check-outline"></i>
                                Laporan Produk Tidak Sesuai (LPTS)
                            </h6>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-2">
                <!-- Checked -->
                <div class="col-xl-4 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm" style="min-height: 120px;">
                        <div class="card-body">
                            <div class="mb-2">
                                <small class="text-muted">Checked</small>
                            </div>
                            <h2 class="mb-2 fw-bold" style="color: #28a745;">{{ $lptsChecked }}</h2>
                            <div>
                                <span class="badge" style="background-color: #28a745; color: white; font-size: 11px;">
                                    +{{ $lptsCheckedToday }} Hari Ini
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Scrap -->
                <div class="col-xl-4 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm" style="min-height: 120px;">
                        <div class="card-body">
                            <div class="mb-2">
                                <small class="text-muted">Scrap</small>
                            </div>
                            <h2 class="mb-2 fw-bold" style="color: #dc3545;">{{ $lptsScrap }}</h2>
                            <div>
                                <span class="badge" style="background-color: #dc3545; color: white; font-size: 11px;">
                                    +{{ $lptsScrapToday }} Hari Ini
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rework -->
                <div class="col-xl-4 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm" style="min-height: 120px;">
                        <div class="card-body">
                            <div class="mb-2">
                                <small class="text-muted">Rework</small>
                            </div>
                            <h2 class="mb-2 fw-bold" style="color: #ffc107;">{{ $lptsRework }}</h2>
                            <div>
                                <span class="badge" style="background-color: #ffc107; color: white; font-size: 11px;">
                                    +{{ $lptsReworkToday }} Hari Ini
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total LPTS (Full Width) -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm" style="min-height: 120px;">
                        <div class="card-body">
                            <div class="mb-2">
                                <small class="text-muted">Total LPTS</small>
                            </div>
                            <h2 class="mb-2 fw-bold" style="color: #333;">{{ $totalLpts }}</h2>
                            <div>
                                <span class="badge" style="background-color: #17a2b8; color: white; font-size: 11px;">
                                    +{{ $lptsTotalToday }} Hari Ini
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- LMTS Section -->
            <div class="row ">
                <div class="col-12">
                    <div class="card border-0" style="background-color: #f0f0f0;">
                        <div class="card-body py-3">
                            <h6 class="mb-0 text-dark">
                                <i class="mdi mdi-clipboard-text-outline"></i>
                                Laporan Material Tidak Sesuai (LMTS)
                            </h6>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-2">
                <!-- Hold -->
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm" style="min-height: 120px;">
                        <div class="card-body">
                            <div class="mb-2">
                                <small class="text-muted">Hold</small>
                            </div>
                            <h2 class="mb-2 fw-bold" style="color: #6c757d;">{{ $lmtsHold }}</h2>
                            <div>
                                <span class="badge" style="background-color: #6c757d; color: white; font-size: 11px;">
                                    +{{ $lmtsHoldToday }} Hari Ini
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Scrap -->
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm" style="min-height: 120px;">
                        <div class="card-body">
                            <div class="mb-2">
                                <small class="text-muted">Scrap</small>
                            </div>
                            <h2 class="mb-2 fw-bold" style="color: #dc3545;">{{ $lmtsScrap }}</h2>
                            <div>
                                <span class="badge" style="background-color: #dc3545; color: white; font-size: 11px;">
                                    +{{ $lmtsScrapToday }} Hari Ini
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Return -->
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm" style="min-height: 120px;">
                        <div class="card-body">
                            <div class="mb-2">
                                <small class="text-muted">Return</small>
                            </div>
                            <h2 class="mb-2 fw-bold" style="color: #17a2b8;">{{ $lmtsReturn }}</h2>
                            <div>
                                <span class="badge" style="background-color: #17a2b8; color: white; font-size: 11px;">
                                    +{{ $lmtsReturnToday }} Hari Ini
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Repair -->
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm" style="min-height: 120px;">
                        <div class="card-body">
                            <div class="mb-2">
                                <small class="text-muted">Repair</small>
                            </div>
                            <h2 class="mb-2 fw-bold" style="color: #ffc107;">{{ $lmtsRepair }}</h2>
                            <div>
                                <span class="badge" style="background-color: #ffc107; color: white; font-size: 11px;">
                                    +{{ $lmtsRepairToday }} Hari Ini
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total LMTS (Full Width) -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm" style="min-height: 120px;">
                        <div class="card-body">
                            <div class="mb-2">
                                <small class="text-muted">Total LMTS</small>
                            </div>
                            <h2 class="mb-2 fw-bold" style="color: #333;">{{ $totalLmts }}</h2>
                            <div>
                                <span class="badge" style="background-color: #17a2b8; color: white; font-size: 11px;">
                                    +{{ $lmtsTotalToday }} Hari Ini
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Return Customer PPIC Section -->
            <div class="row ">
                <div class="col-12">
                    <div class="card border-0" style="background-color: #f0f0f0;">
                        <div class="card-body py-3">
                            <h6 class="mb-0 text-dark">
                                <i class="mdi mdi-package-variant-closed"></i>
                                Return Customer PPIC
                            </h6>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-2">
                <!-- Checked -->
                <div class="col-xl-4 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm" style="min-height: 120px;">
                        <div class="card-body">
                            <div class="mb-2">
                                <small class="text-muted">Checked</small>
                            </div>
                            <h2 class="mb-2 fw-bold" style="color: #28a745;">{{ $returnChecked }}</h2>
                            <div>
                                <span class="badge" style="background-color: #28a745; color: white; font-size: 11px;">
                                    +{{ $returnCheckedToday }} Hari Ini
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Scrap -->
                <div class="col-xl-4 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm" style="min-height: 120px;">
                        <div class="card-body">
                            <div class="mb-2">
                                <small class="text-muted">Scrap</small>
                            </div>
                            <h2 class="mb-2 fw-bold" style="color: #dc3545;">{{ $returnScrap }}</h2>
                            <div>
                                <span class="badge" style="background-color: #dc3545; color: white; font-size: 11px;">
                                    +{{ $returnScrapToday }} Hari Ini
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rework -->
                <div class="col-xl-4 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm" style="min-height: 120px;">
                        <div class="card-body">
                            <div class="mb-2">
                                <small class="text-muted">Rework</small>
                            </div>
                            <h2 class="mb-2 fw-bold" style="color: #ffc107;">{{ $returnRework }}</h2>
                            <div>
                                <span class="badge" style="background-color: #ffc107; color: white; font-size: 11px;">
                                    +{{ $returnReworkToday }} Hari Ini
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Return Customer (Full Width) -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm" style="min-height: 120px;">
                        <div class="card-body">
                            <div class="mb-2">
                                <small class="text-muted">Total Return Customer</small>
                            </div>
                            <h2 class="mb-2 fw-bold" style="color: #333;">{{ $totalReturnCustomer }}</h2>
                            <div>
                                <span class="badge" style="background-color: #17a2b8; color: white; font-size: 11px;">
                                    +{{ $returnTotalToday }} Hari Ini
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Data Waste Section -->
            <div class="row ">
                <div class="col-12">
                    <div class="card border-0" style="background-color: #f0f0f0;">
                        <div class="card-body py-3">
                            <h6 class="mb-0 text-dark">
                                <i class="mdi mdi-delete"></i>
                                Data Waste
                            </h6>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm" style="min-height: 120px;">
                        <div class="card-body">
                            <div class="mb-2">
                                <small class="text-muted">Total Data Waste</small>
                            </div>
                            <h2 class="mb-2 fw-bold" style="color: #333;">{{ $totalDataWaste }}</h2>
                            <div>
                                <small class="text-muted">All Time</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
