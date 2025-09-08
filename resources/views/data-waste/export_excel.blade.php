<!-- filepath: e:\Projek_Qc\otp_qc\resources\views\data-waste\export_excel.blade.php -->
<table border="1">
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>No Report</th>
            <th>No SO</th>
            <th>Work Center</th>
            <th>Weight</th>
            <th>Unit</th>
            <th>Group Sub</th>
            <th>Type Product</th>
            <th>Type Stock</th>
            <th>Status</th>
            <th>Remark</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($datas as $row)
            <tr>
                <td>{{ $row->waste_date ?? ($row->tanggal ?? '-') }}</td>
                <td>{{ $row->no_report ?? '-' }}</td>
                <td>{{ $row->no_so ?? '-' }}</td>
                <td>{{ $row->work_center ?? '-' }}</td>
                <td>{{ $row->weight ?? '-' }}</td>
                <td>{{ $row->unit ?? '-' }}</td>
                <td>{{ $row->group_sub ?? '-' }}</td>
                <td>{{ $row->type_product ?? '-' }}</td>
                <td>{{ $row->type_stock ?? '-' }}</td>
                <td>{{ $row->status ?? '-' }}</td>
                <td>{{ $row->remark ?? '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
