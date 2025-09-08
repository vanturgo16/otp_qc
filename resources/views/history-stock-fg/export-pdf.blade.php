<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Export Stock FG</title>
  <style>
    table { width:100%; border-collapse: collapse; font-size: 12px; }
    th, td { border:1px solid #333; padding:6px; }
  </style>
</head>
<body>
  <h3>Export Stock FG</h3>
  <table>
    <thead>
      <tr>
        <th>Number</th><th>Type Product</th><th>Qty</th><th>Weight</th>
        <th>Type Stock</th><th>Date</th><th>Status</th><th>Remark</th>
        <th>Product Code</th><th>Description</th><th>Customer</th>
      </tr>
    </thead>
    <tbody>
      @foreach($rows as $r)
      <tr>
        <td>{{ $r->number }}</td>
        <td>{{ $r->type_product }}</td>
        <td>{{ $r->qty }}</td>
        <td>{{ $r->weight }}</td>
        <td>{{ $r->type_stock }}</td>
        <td>{{ $r->date }}</td>
        <td>{{ $r->status }}</td>
        <td>{{ $r->remarks }}</td>
        <td>{{ $r->product_code }}</td>
        <td>{{ $r->description }}</td>
        <td>{{ $r->customer_name }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</body>
</html>
