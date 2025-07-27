<table border="1">
    <thead>
        <tr>
            <th>SO Number</th>
            <th>No Sample</th>
            <th>Request Date</th>
            <th>Customer</th>
            <th>Marketing</th>
            <th>Product/Item</th>
            <th>Sample Type</th>
            <th>Perforasi</th>
            <th>QTY</th>
            <th>Unit</th>
            <th>Barcode</th>
            <th>Weight</th>
            <th>Type Product</th>
            <th>Done Date</th>
            <th>Submission Date</th>
            <th>Done Duration</th>
            <th>Remarks</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($dataSoSamples as $data)
        <tr>
            <td>{{ $data->so_number }}</td>
            <td>{{ $data->no_sample }}</td>
            <td>{{ $data->request_date }}</td>
            <td>{{ $data->customer_name }}</td>
            <td>{{ $data->sales_name }}</td>
            <td>{{ $data->product_item }}</td>
            <td>{{ $data->type }}</td>
            <td>{{ $data->perforasi }}</td>
            <td>{{ $data->qty }}</td>
            <td>{{ $data->unit }}</td>
            <td>{{ $data->all_barcodes }}</td>
            <td>{{ $data->weight }}</td>
            <td>{{ $data->type_product }}</td>
            <td>{{ $data->sample_done_date }}</td>
            <td>{{ $data->sample_submission_date }}</td>
            <td>{{ $data->done_duration }}</td>
            <td>{{ $data->remarks }}</td>
            <td>
                @if ($data->sample_done_date == null && $data->sample_submission_date == null)
                    Progress
                @elseif($data->sample_done_date != null && $data->sample_submission_date == null)
                    Open
                @elseif($data->sample_done_date != null && $data->sample_submission_date != null)
                    Closed
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
