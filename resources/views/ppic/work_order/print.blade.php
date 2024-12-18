<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sales ORder {{ $salesOrder->so_number }}</title>
    <link rel="shortcut icon" href="{{ asset('assets/images/icon-otp.png') }}">
    <!-- Bootstrap Css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />

    <style>
        * {
            font-family: "Segoe UI", Arial, sans-serif;
        }

        /* table, .footer {
            font-size: 1rem;
        } */
    </style>

</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-8 d-flex align-items-center gap-10">
                <strong class="fs-4">PT OLEFINA TIFAPLAS POLIKEMINDO</strong>
            </div>
            {{-- <div class="col-4 d-flex justify-content-end">
                FM-SM-MKT-02, Rev. 0, 01 September 2021
            </div> --}}
        </div>

        <div class="row text-center">
            <div class="col-12">
                <strong class="fs-3">WORK ORDER</strong>
                <hr>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <table>
                    <tr>
                        <td style="width: 150px;">No. SO</td>
                        <td>:</td>
                        <td>{{ $salesOrder->so_number }}</td>
                        <td style="width: 100px;"></td>
                        <td style="width: 150px;">Marketing</td>
                        <td>:</td>
                        <td>{{ $salesOrder->masterSalesman->name }}</td>
                    </tr>
                    <tr>
                        <td>Product Code</td>
                        <td>:</td>
                        <td>{{ $product->product_code }}</td>
                        <td style="width: 100px;"></td>
                        <td>Customer</td>
                        <td>:</td>
                        <td>{{ $salesOrder->masterCustomer->name }}</td>
                    </tr>
                    <tr>
                        <td>Product Name</td>
                        <td>:</td>
                        <td>{{ $product->description }}</td>
                        <td style="width: 100px;"></td>
                        <td>Due Date</td>
                        <td>:</td>
                        <td>{{ \Carbon\Carbon::parse($salesOrder->due_date)->isoFormat('D/MM/YYYY', 'ID') }}</td>
                    </tr>
                    <tr>
                        <td>Size</td>
                        <td>:</td>
                        <td>{{ $product->thickness . ' MIC X ' . $product->width . ' MM X ' . $product->height . ' M' }}
                        </td>
                        <td style="width: 100px;"></td>
                        <td>SO Date</td>
                        <td>:</td>
                        <td>{{ \Carbon\Carbon::parse($salesOrder->date)->isoFormat('D/MM/YYYY', 'ID') }}</td>
                    </tr>
                    <tr>
                        <td>Perforasi</td>
                        <td>:</td>
                        <td>{{ $product->perforasi }}</td>
                        <td style="width: 100px;"></td>
                        <td>Order Qty</td>
                        <td>:</td>
                        <td>{{ $salesOrder->qty . ' ' . $salesOrder->masterUnit->unit_code }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <table class="w-100">
                    <thead style="border: 1px solid black;">
                        <tr>
                            <th>Item Code</th>
                            <th>Item Description</th>
                            <th>Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($work_order_details as $item)
                            <tr style="border-bottom: 1px solid black; height: 35px;">
                                <td class="p-1" style="width: 25%;">WO No : {{ $item->wo_number }}</td>
                                <td class="p-1" >
                                    Process : {{ $item->process_code }} &nbsp;
                                    Machine : {{ $item->work_center_code }} &nbsp;
                                    Qty Process : {{ $item->qty . ' ' . $item->unit_code . ' X 1 ' . $item->unit_code }}
                                </td>
                                <td class="p-1 text-end" style="width: 10%;">{{ $item->qty . ' ' . $item->unit_code }}</td>
                            </tr>
                            <tr style="border-bottom: 1px solid black; height: 30px;">
                                <td class="p-1"  style="width: 25%; padding-left: 30px !important;">{{ $item->pc_needed }}</td>
                                <td class="p-1" >{{ $item->dsc }}</td>
                                <td class="p-1 text-end" style="width: 10%;">{{ $item->qty_needed . ' ' . $item->unit_needed }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <hr>
        <div class="row footer">
            <h6>Keterangan :</h6>
            <div class="col-4 text-center">
                <p class="mb-5">Diketahui Oleh,</p>
                <p><b>(.............................................)</b></p>
            </div>
            <div class="col-4 text-center">
                <p class="mb-5">Diketahui Oleh,</p>
                <p><b>(.............................................)</b></p>
            </div>
            <div class="col-4 text-center">
                <p class="mb-5">Dibuat Oleh,</p>
                <p><b>(.............................................)</b></p>
            </div>
        </div>





    </div>
</body>

</html>
