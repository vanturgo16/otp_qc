@php use Carbon\Carbon; @endphp
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Laporan Finish Good (FG)</title>
  <style>
    body{font-family: DejaVu Sans, Arial, Helvetica, sans-serif; font-size:12px}
    table{border-collapse:collapse; width:100%}
    th,td{border:1px solid #000; padding:6px}
    .no-border td{border:0;padding:3px}
    .title{font-size:18px; font-weight:bold; text-align:center}
    .subtitle{font-size:14px; text-align:center; margin-bottom:16px}
    .muted{color:#555}
    .bg{background:#e5e5e5; font-weight:bold}
    .right{text-align:right}
    .center{text-align:center}
  </style>
</head>
<body>
  <div class="title">Laporan History Stock Sample</div>
  <div class="subtitle">PT Olefina Tifaplas Polikemindo</div>

  <table class="no-border" style="margin:10px 0 12px 0;">
   s
    <tr><td>Periode</td><td>: {{ $periodHuman }}</td></tr>
    <tr><td>Stok Awal</td><td>: 0</td></tr>
    <tr><td>Stok Akhir</td><td>: 0</td></tr>
    <tr><td>Di Cetak Oleh</td><td>: {{ auth()->user()->email ?? 'system' }} at {{ now()->format('d-m-Y H:i:s') }}</td></tr>
  </table>

  <table>
    <thead>
      <tr class="bg">
        <th style="width:45px;" class="center">No</th>
        <th>(Lot/Report/Packing) Number</th>
        <th style="width:120px;" class="center">Tanggal</th>
        <th style="width:80px;" class="center">IN</th>
        <th style="width:80px;" class="center">OUT</th>
      </tr>
    </thead>
    <tbody>
      @foreach($rows as $i => $r)
        <tr>
          <td class="center">{{ $i+1 }}</td>
          <td>{{ $r->lot_number }}</td>
          <td class="center">{{ Carbon::parse($r->date)->format('Y-m-d') }}</td>
          <td class="right">{{ $r->type_stock==='IN'  ? number_format($r->qty,0,',','.') : '0' }}</td>
          <td class="right">{{ $r->type_stock==='OUT' ? number_format($r->qty,0,',','.') : '0' }}</td>
        </tr>
      @endforeach
      <tr class="bg">
        <td colspan="3"><b>Jumlah</b></td>
        <td class="right"><b>{{ number_format($totalIn,0,',','.') }}</b></td>
        <td class="right"><b>{{ number_format($totalOut,0,',','.') }}</b></td>
      </tr>
      <tr class="bg">
        <td colspan="3"><b>Total</b></td>
        <td colspan="2" class="right"><b>{{ number_format($totalIn - $totalOut,0,',','.') }}</b></td>
      </tr>
    </tbody>
  </table>
</body>
</html>
