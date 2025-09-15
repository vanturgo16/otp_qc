@php use Carbon\Carbon; @endphp
<table>
  <tr><td colspan="5"><b>Laporan History Stock Sample </b></td></tr>
  <tr><td colspan="5"><b>PT Olefina Tifaplas Polikemindo</b></td></tr>
  <tr><td>Periode</td><td>:</td><td colspan="3">{{ $periodHuman }}</td></tr>
  <tr><td>Stok Awal</td><td>:</td><td>0</td></tr>
  <tr><td>Stok Akhir</td><td>:</td><td>0</td></tr>
  <tr><td>Di Export Oleh</td><td>:</td><td colspan="3">{{ auth()->user()->email ?? 'system' }} at {{ now()->format('d-m-Y H:i:s') }}</td></tr>
</table>

<table>
  <thead>
    <tr>
      <th>No</th>
      <th>(Lot/Report/Packing) Number</th>
      <th>Tanggal</th>
      <th>IN</th>
      <th>OUT</th>
    </tr>
  </thead>
  <tbody>
    @foreach($rows as $i => $r)
      <tr>
        <td>{{ $i+1 }}</td>
        <td>{{ $r->lot_number }}</td>
        <td>{{ Carbon::parse($r->date)->format('Y-m-d') }}</td>
        <td>{{ $r->type_stock==='IN'  ? $r->qty : 0 }}</td>
        <td>{{ $r->type_stock==='OUT' ? $r->qty : 0 }}</td>
      </tr>
    @endforeach
    <tr>
      <td colspan="3"><b>Jumlah</b></td>
      <td><b>{{ $totalIn }}</b></td>
      <td><b>{{ $totalOut }}</b></td>
    </tr>
    <tr>
      <td colspan="3"><b>Total</b></td>
      <td colspan="2"><b>{{ $totalIn - $totalOut }}</b></td>
    </tr>
  </tbody>
</table>
