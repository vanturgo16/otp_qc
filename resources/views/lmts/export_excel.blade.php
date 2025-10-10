<table>
    <thead>
        <tr>
            <th>No</th>
            <th>No LMTS</th>
            <th>GRN</th>
            <th>Lot Number</th>
            <th>External Lot</th>
            <th>Product Description</th>
            <th>Date</th>
            <th>Total GLQ</th>
            <th>Unit</th>
            <th>Type Product</th>
            <th>Status</th>
            <th>Supplier</th>
            <th>Remarks</th>
            <th>Created At</th>
        </tr>
    </thead>
    <tbody>
        @foreach($datas as $index => $data)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $data->no_lmts }}</td>
            <td>{{ $data->receipt_number }}</td>
            <td>{{ $data->lot_number }}</td>
            <td>{{ $data->external_lot }}</td>
            <td>{{ $data->description }}</td>
            <td>{{ $data->date_formatted }}</td>
            <td>{{ $data->total_glq }}</td>
            <td>{{ $data->unit }}</td>
            <td>{{ $data->type_product }}</td>
            <td>{{ $data->status_text }}</td>
            <td>{{ $data->name }}</td>
            <td>{{ $data->remarks }}</td>
            <td>{{ $data->created_at_formatted }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
