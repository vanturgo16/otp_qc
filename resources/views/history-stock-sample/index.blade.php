@extends('layouts.master')
@section('konten')
    @php
        use Carbon\Carbon;
        Carbon::setLocale('id');
    @endphp

    {{-- ====== small styles biar mirip screenshot ====== --}}
    <style>
        .modal-purple .modal-header {
            background: #5b57d1;
            color: #fff
        }

        .modal-purple .btn-close {
            filter: invert(1);
            opacity: .9
        }

        .btn-badge {
            padding: .25rem .5rem
        }

        .badge-pill {
            border-radius: 999px;
            padding: .35rem .55rem;
            font-weight: 600
        }

        .badge-closed {
            background: #16a34a
        }

        /* green */
        .badge-posted {
            background: #9ca3af
        }

        /* gray */
        .badge-in {
            background: #22c55e
        }

        /* green */
        .badge-out {
            background: #ef4444
        }

        /* red */
        .btn-detail {
            background: #3b82f6;
            border-color: #3b82f6
        }

        .btn-detail:hover {
            filter: brightness(.95)
        }

        .btn-export {
            background: #e5e7eb;
            border-color: #e5e7eb
        }
    </style>

    <div class="page-content">
        <div class="container-fluid">

            {{-- ====== TITLE & BREADCRUMB ====== --}}
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <div class="page-title-left">
                            <h4 class="mb-0">History Stock Sample — Index</h4>
                        </div>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript:void(0)">Master Data</a></li>
                                <li class="breadcrumb-item active">History Stock Sample</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            @include('layouts.alert')

            {{-- ====== TABLE CARD ====== --}}
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-light p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0"><b>History Stock (Global)</b></h6>
                                </div>
                                <button class="btn btn-sm btn-primary" disabled>
                                    <i class="mdi mdi-clock"></i>
                                    @if (($searchDate ?? 'All') === 'Custom' && !empty($month))
                                        {{ \Carbon\Carbon::parse($month)->translatedFormat('F Y') }}
                                    @else
                                        <strong>ALL</strong>
                                    @endif
                                </button>

                            </div>
                        </div>

                        <div class="card-body">
                            {{-- ====== Controls: length + export | filter + search ====== --}}
                            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2"
                                id="custom-controls">
                                <div class="d-flex align-items-center gap-2">
                                    <label class="mb-0">
                                        <select id="lengthDT" class="form-select form-select-sm">
                                            <option value="5" @selected(true)>5</option>
                                            <option value="10">10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                            <option value="-1">All</option>
                                        </select>
                                    </label>
                                    <button class="btn btn-sm btn-export waves-effect btn-label waves-light"
                                        data-bs-toggle="modal" data-bs-target="#exportModal">
                                        <i class="mdi mdi-export label-icon"></i> Export Data
                                    </button>
                                    {{-- ====== Modal Export ====== --}}
                                    <div class="modal fade" id="exportModal" data-bs-backdrop="static"
                                        data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-md">
                                            <div class="modal-content modal-purple">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"><i class="mdi mdi-export me-1"></i> Export Data
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>

                                                <form action="{{ route('historystock.fg.export') }}" method="POST"
                                                    target="_blank">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Select Period</label>
                                                            <input type="month" name="month" class="form-control"
                                                                required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer d-flex justify-content-between">
                                                        <button type="submit" name="export_type" value="pdf"
                                                            class="btn btn-danger">
                                                            <i class="mdi mdi-file-pdf"></i> Print To PDF
                                                        </button>
                                                        <button type="submit" name="export_type" value="excel"
                                                            class="btn btn-success">
                                                            <i class="mdi mdi-file-excel"></i> Export To Excel
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="input-group" style="max-width: 360px;">
                                    <button class="btn btn-light" type="button" data-bs-toggle="modal"
                                        data-bs-target="#filterMonthModal">
                                        <i class="mdi mdi-filter"></i> Period Filter
                                    </button>
                                    <input class="form-control" id="custom-search-input" type="text"
                                        placeholder="Search...">
                                </div>
                            </div>

                            {{-- ====== TABLE (client-side) ====== --}}
                            <table class="table table-bordered table-hover dt-responsive w-100" id="ssTable"
                                style="font-size: small">
                                <thead>
                                    <tr>
                                        <th class="align-middle text-center">#</th>
                                        <th class="align-middle text-center">(Lot/Report/Packing) Number</th>
                                        <th class="align-middle text-center">Type Product</th>
                                        <th class="align-middle text-center">Qty</th>
                                        <th class="align-middle text-center">Weight</th>
                                        <th class="align-middle text-center">Type Stock</th>
                                        <th class="align-middle text-center">Date</th>
                                        <th class="align-middle text-center">Status</th>
                                        <th class="align-middle text-center">Remark</th>
                                        <th class="align-middle text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rows as $i => $r)
                                        <tr>
                                            {{-- # (nomor urut) --}}
                                            <td class="align-middle text-center text-bold freeze-column">{{ $i + 1 }}
                                            </td>

                                            {{-- Lot/Packing Number: pl.packing_number atau hs.id_good_receipt_notes_details --}}
                                            <td class="align-middle text-bold freeze-column">
                                                {{ $r->lot_number ?? ($r->packing_number ?? ($r->id_good_receipt_notes_details ?? '-')) }}
                                            </td>

                                            {{-- Type Product: hs.type_product --}}
                                            <td class="align-middle text-center">{{ $r->type_product ?? '-' }}</td>

                                            {{-- Qty: hs.qty --}}
                                            <td class="align-middle text-end text-bold">
                                                @php
                                                    $v = $r->qty ?? 0;
                                                    echo $v
                                                        ? (strpos(strval($v), '.') !== false
                                                            ? rtrim(rtrim(number_format($v, 6, ',', '.'), '0'), ',')
                                                            : number_format($v, 0, ',', '.'))
                                                        : '0';
                                                @endphp
                                            </td>

                                            {{-- Weight: hs.weight --}}
                                            <td class="align-middle text-end text-bold">
                                                @php
                                                    $w = $r->weight ?? 0;
                                                    echo $w
                                                        ? (strpos(strval($w), '.') !== false
                                                            ? rtrim(rtrim(number_format($w, 6, ',', '.'), '0'), ',')
                                                            : number_format($w, 0, ',', '.'))
                                                        : '0';
                                                @endphp
                                            </td>

                                            {{-- Type Stock: hs.type_stock (klik => modal info) --}}
                                            <td class="align-middle text-center">
                                                @if ($r->type_stock === 'IN')
                                                    <button type="button"
                                                        class="btn btn-light btn-sm btn-badge btn-type-stock"
                                                        data-type="IN" data-bs-toggle="modal"
                                                        data-bs-target="#infoStockModal">
                                                        <i class="mdi mdi-arrow-down-bold"></i>
                                                        <span class="badge badge-pill badge-in text-white">IN</span>
                                                    </button>
                                                @elseif($r->type_stock === 'OUT')
                                                    <button type="button"
                                                        class="btn btn-light btn-sm btn-badge btn-type-stock"
                                                        data-type="OUT" data-bs-toggle="modal"
                                                        data-bs-target="#infoStockModal">
                                                        <i class="mdi mdi-arrow-up-bold"></i>
                                                        <span class="badge badge-pill badge-out text-white">OUT</span>
                                                    </button>
                                                @else
                                                    {{ $r->type_stock ?? '-' }}
                                                @endif
                                            </td>

                                            {{-- Date: hs.date (format Y-m-d seperti gambar) --}}
                                            <td class="align-middle text-center">
                                                {{ $r->date ? Carbon::parse($r->date)->format('Y-m-d') : '-' }}
                                            </td>

                                            {{-- Status: pl.status --}}
                                            <td class="align-middle text-center">
                                                @php $st = $r->status ?? '-'; @endphp
                                                @if ($st === 'Closed')
                                                    <span class="badge bg-success text-white">Closed</span>
                                                @elseif($st === 'Posted')
                                                    <span class="badge bg-secondary text-white">Posted</span>
                                                @elseif($st === 'Request')
                                                    <span class="badge bg-primary text-white">Request</span>
                                                @else
                                                    <span class="badge bg-light text-dark">{{ $st }}</span>
                                                @endif
                                            </td>

                                            {{-- Remark: hs.remarks (pakai "s") --}}
                                            <td class="align-middle">{{ $r->remarks ?? '-' }}</td>

                                            {{-- Action: Detail --}}
                                            <td class="align-middle text-center">
                                                <a href="{{ route('historystock.fg.show', encrypt($r->id)) }}"
                                                    class="btn btn-sm btn-info waves-effect btn-label waves-light">
                                                    <i class="mdi mdi-information-outline label-icon"></i> Detail
                                                </a>
                                            </td>

                                            {{-- nanti sambungkan: href="{{ route('historystock.fg.show', $r->id) }}" --}}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- ====== DataTables CLIENT-SIDE + interaksi ====== --}}
    <script>
        $(function() {
            const dt = $('#ssTable').DataTable({
                scrollX: true,
                responsive: false,
                processing: false,
                serverSide: false,
                pageLength: 5, // sesuai contoh gambar
                lengthMenu: [
                    [5, 10, 25, 50, 100, -1],
                    [5, 10, 25, 50, 100, "All"]
                ],
                order: [
                    [6, 'desc']
                ], // sort default by Date (kolom index 6)
                dom: 't<"d-flex justify-content-between align-items-center mt-2"lip>',
            });

            $('#lengthDT').on('change', function() {
                dt.page.len(this.value).draw();
            });
            $('#custom-search-input').on('keyup change', function() {
                dt.search(this.value).draw();
            });

            // Klik IN/OUT => set isi modal info
            $(document).on('click', '.btn-type-stock', function() {
                const t = $(this).data('type'); // IN | OUT
                const text = t === 'OUT' ? 'Stok Barang Keluar' : 'Stok Barang Masuk';
                $('#infoStockText').text(text);
            });
        });
    </script>

    {{-- ====== Modal Filter By Month ====== --}}
    <div class="modal fade" id="filterMonthModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content modal-purple">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="mdi mdi-filter me-1"></i> Period Filter</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="{{ route('historystock.fg') }}" method="GET">
                    <div class="modal-body" style="max-height: 67vh; overflow-y: auto;">
                        <div class="mb-3">
                            <label class="form-label">Filter Date</label>
                            <select class="form-select" id="filterMode" name="searchDate">
                                <option value="All" @selected(($searchDate ?? 'All') === 'All')>All</option>
                                <option value="Custom" @selected(($searchDate ?? '') === 'Custom')>Custom</option>
                            </select>



                        </div>
                        <hr>
                        <div class="mb-2">
                            <label class="form-label">Select Period</label>
                            <input type="month" id="monthInput" name="month" class="form-control"
                                value="{{ $month }}"
                                {{ ($searchDate ?? 'All') === 'All' ? 'readonly' : 'required' }}>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-info waves-effect btn-label waves-light">
                            <i class="mdi mdi-filter label-icon"></i> Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(function() {
            // Ganti mode filter => atur readonly/required pada input month
            $('#filterMode').on('change', function() {
                const v = this.value; // All | Custom
                if (v === 'All') {
                    $('#monthInput').val('').prop('readonly', true).prop('required', false);
                } else {
                    $('#monthInput').prop('readonly', false).prop('required', true);
                }
            });
        });
    </script>

    {{-- ====== Modal Info (dipakai IN/OUT) ====== --}}
    <div class="modal fade" id="infoStockModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content modal-purple">
                <div class="modal-header">
                    <h5 class="modal-title">Info</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <p id="infoStockText" class="mb-0">—</p>
                </div>
            </div>
        </div>
    </div>
@endsection
