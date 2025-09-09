@extends('layouts.master')
@section('konten')
@php use Carbon\Carbon; Carbon::setLocale('id'); @endphp

<style>
  .section-title {
    background: #edeef2; /* mirip bg-muted abu2 di screenshot */
    border-radius: .25rem;
    padding: .9rem 1rem;
    font-weight: 600;
  }
  .kv th { width: 220px; }
  .kv th, .kv td { padding: .9rem 1rem; vertical-align: middle; }
</style>

<div class="page-content">
  <div class="container-fluid">

    {{-- Back + breadcrumb --}}
    <div class="row">
      <div class="col-12">
        <div class="d-sm-flex align-items-center justify-content-between mb-3">
          <div>
            <a href="{{ route('historystock.fg', array_filter(['searchDate'=>$searchDate ?? null, 'month'=>$month ?? null])) }}"
               class="btn btn-light waves-effect btn-label waves-light">
              <i class="mdi mdi-arrow-left label-icon"></i> Back To List History Stock FG
            </a>
          </div>
          <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Master Data</a></li>
            <li class="breadcrumb-item"><a href="{{ route('historystock.fg') }}">List Stock FG</a></li>
            <li class="breadcrumb-item active">Detail {{ $row->packing_number ?? '-' }}</li>
          </ol>
        </div>
      </div>
    </div>

    {{-- Kartu utama --}}
    <div class="card">
      <div class="card-body">

        {{-- Tabel identitas utama --}}
        <table class="table border">
          <tr>
            <th>Product Code</th>
            <td>: {{ $row->product_code ?? '-' }}</td>
          </tr>
          <tr>
            <th>Description</th>
            <td>: {{ $row->description ?? '-' }}</td>
          </tr>
          <tr>
            <th>Packing Number</th>
            <td>: {{ $row->packing_number ?? '-' }}</td>
          </tr>
        </table>

        {{-- Sub-header detail packing --}}
        <div class="section-title mt-2 mb-3">
          Detail Packing Number <span class="fw-bold">{{ $row->packing_number ?? '-' }}</span>
        </div>

        {{-- Detail kolom kiri sesuai screenshot --}}
        <div class="px-2">
          <div class="mb-3">
            <div class="fw-semibold">Date :</div>
            <div>{{ $row->date ? Carbon::parse($row->date)->format('Y-m-d') : '-' }}</div>
          </div>

          <div class="mb-3">
            <div class="fw-semibold">Customer :</div>
            <div>{{ $row->customer_name ?? '-' }}</div>
          </div>

          <div class="mb-1">
            <div class="fw-semibold">Status :</div>
            <div>
              @php $st = $row->status ?? '-'; @endphp
              @if($st === 'Posted')
                <span class="badge bg-secondary">Posted</span>
              @elseif($st === 'Closed')
                <span class="badge bg-success">Closed</span>
              @elseif($st === 'Request')
                <span class="badge bg-primary">Request</span>
              @else
                <span class="badge bg-light text-dark">{{ $st }}</span>
              @endif
            </div>
          </div>
        </div>

      </div>
    </div>

  </div>
</div>
@endsection
