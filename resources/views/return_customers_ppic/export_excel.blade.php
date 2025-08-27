<table border="1">
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>No DN</th>
            <th>Customer</th>
            <th>No PO</th>
            <th>No SO</th>
            <th>Nama Produk</th>
            <th>Qty</th>
            <th>Unit</th>
            <th>Berat</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($returns as $return)
            <tr>
                <td>{{ $return->date_return }}</td>
                <td>{{ $return->dn_number }}</td>
                <td>{{ $return->customer_name }}</td>
                <td>{{ $return->no_po }}</td>
                <td>{{ $return->so_number }}</td>
                <td>{{ $return->name }}</td>
                <td>{{ $return->qty }}</td>
                <td>{{ $return->unit }}</td>
                <td>{{ $return->weight }}</td>
                <td>{{ $return->keterangan }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
