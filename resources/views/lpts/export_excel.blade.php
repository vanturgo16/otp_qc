<table border="1">
    <thead>
        <tr>
            <th>No LPTS</th>
            <th>Packing Number</th>
            <th>Barcode</th>
            <th>WO Number</th>
            <th>Operator</th>
            <th>Description</th>
            <th>Thickness</th>
            <th>Type Product</th>
            <th>Group Sub</th>
            <th>QTY</th>
            <th>Unit</th>
            <th>Weight</th>
            <th>Date Report</th>
            <th>Status</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($datas as $data)
            <tr>
                <td>{{ $data->no_lpts }}</td>
                <td>{{ $data->packing_number }}</td>
                <td>{{ $data->barcode_number }}</td>
                <td>{{ $data->wo_number }}</td>
                <td>{{ $data->staff }}</td>
                <td>{{ $data->description }}</td>
                <td>{{ $data->thickness }}</td>
                <td>{{ $data->type_product }}</td>
                <td>{{ $data->group_sub_name }}</td>
                <td>{{ $data->qty }}</td>
                <td>{{ $data->unit }}</td>
                <td>{{ $data->weight }}</td>
                <td>{{ $data->created_at_formatted }}</td>
                <td>{{ $data->status }}</td>
                <td>{{ $data->keterangan }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
