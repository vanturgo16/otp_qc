<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>STOCK CARD WASTE</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px;
        }

        .header {
            font-size: 18px;
            font-weight: bold;
        }

        .green {
            background: #b6fcb6;
            font-weight: bold;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }
    </style>
</head>

<body>
    <table style="width:100%; border:1px solid #000;">
        <tr>
            <td style="width: 15%; border:none;">
                <img src="{{ public_path('assets/images/icon-otp.png') }}" style="width: 90px;">
            </td>
            <td style="border:none; text-align:center;">
                <div class="header">STOCK CARD WASTE</div>
            </td>
        </tr>
    </table>
    @php
        $typeProductsSelected = $typeProductsSelected ?? [];
        $typeProducts = count($typeProductsSelected)
            ? $typeProductsSelected
            : collect($datas)
                ->pluck('type_product')
                ->filter(fn($val) => !empty($val))
                ->unique()
                ->sort()
                ->values()
                ->toArray();

        // Inisialisasi saldo, running weight, running unit per type
        $saldo = [];
        $runningWeight = [];
        $runningUnit = [];
        foreach ($typeProducts as $tp) {
            $saldo[$tp] = 0;
            $runningWeight[$tp] = 0;
            $runningUnit[$tp] = '';
        }
        // Hitung jumlah kolom tetap sebelum saldo
        $fixedColspan = 3 + 4 + 5; // Tanggal, Type Product, Work Center + IN(4) + OUT(5)
    @endphp
    <table style="margin-top:8px; width:100%;">
        <tr style="font-weight:bold; text-align:center;">
            <th rowspan="3">TANGGAL</th>
            <th rowspan="3">Type Product</th>
            <th rowspan="3">Work Center</th>
            <th colspan="4">IN</th>
            <th colspan="5">OUT</th>
            <th colspan="{{ count($typeProducts) * 2 }}">SALDO</th>
        </tr>
        <tr style="font-weight:bold; text-align:center;">
            {{-- IN --}}
            <th>Weight</th>
            <th>Unit</th>
            <th>Status</th>
            <th>Keterangan</th>
            {{-- OUT  --}}
            <th>Report Number</th>
            <th>Weight</th>
            <th>Unit</th>
            <th>Status</th>
            <th>Keterangan</th>
            @foreach ($typeProducts as $tp)
                <th colspan="2"> {{ $tp }}</th>
            @endforeach
        </tr>

        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>



            @foreach ($typeProducts as $tp)
                <th> weight</th>
                <th>unit</th>
            @endforeach


        </tr>
        @foreach ($datas as $row)
            @php
                $type_product = $row->type_product ?? '-';
                $unit = $row->unit ?? '';
                $weight = floatval($row->weight ?? 0);

                // Kalkulasi saldo dan running weight/unit per type
                foreach ($typeProducts as $tp) {
                    if ($type_product == $tp) {
                        if (strtoupper($row->type_stock) == 'IN') {
                            $saldo[$tp] += $weight;
                        } elseif (strtoupper($row->type_stock) == 'OUT') {
                            $saldo[$tp] -= $weight;
                        }
                        // Running weight pada baris ini
                        $runningWeight[$tp] = $weight;
                        // Isi unit jika ada
                        if (!empty($unit)) {
                            $runningUnit[$tp] = $unit;
                        }
                    }
                }
            @endphp
            <tr>
                <td class="center">{{ $row->tanggal ? \Carbon\Carbon::parse($row->tanggal)->format('m/d/Y') : '-' }}
                </td>
                <td class="center">{{ $type_product }}</td>
                <td class="center">{{ $row->work_center ?? '-' }}</td>
                {{-- IN --}}
                <td class="right">{{ strtoupper($row->type_stock) == 'IN' ? number_format($weight, 2) : '' }}</td>
                <td class="center">{{ strtoupper($row->type_stock) == 'IN' ? $unit : '' }}</td>
                <td class="center">{{ strtoupper($row->type_stock) == 'IN' ? $row->status : '' }}</td>
                <td class="center" style="border-right: none;">
                    {{ strtoupper($row->type_stock) == 'IN' ? $row->keterangan ?? ($row->remark ?? '') : '' }}</td>
                {{-- OUT --}}
                <td class="center">
                    {{ strtoupper($row->type_stock) == 'OUT' ? $row->report_number ?? ($row->no_report ?? '') : '' }}
                </td>
                <td class="right">{{ strtoupper($row->type_stock) == 'OUT' ? number_format($weight, 2) : '' }}</td>
                <td class="center">{{ strtoupper($row->type_stock) == 'OUT' ? $unit : '' }}</td>
                <td class="center">{{ strtoupper($row->type_stock) == 'OUT' ? $row->status : '' }}</td>
                <td class="center">
                    {{ strtoupper($row->type_stock) == 'OUT' ? $row->keterangan ?? ($row->remark ?? '') : '' }}</td>
                {{-- SALDO --}}
                @foreach ($typeProducts as $tp)
                    @if ($type_product == $tp)
                        <td class="right">{{ number_format($saldo[$tp], 2) }}</td>
                        <td class="center">{{ $runningUnit[$tp] }}</td>
                    @else
                        <td></td>
                        <td></td>
                    @endif
                @endforeach
            </tr>
        @endforeach
        @for ($i = count($datas); $i < 4; $i++)
            <tr>
                <td colspan="{{ $fixedColspan }}"></td>
                @foreach ($typeProducts as $tp)
                    <td></td>
                    <td></td>
                @endforeach
            </tr>
        @endfor
        <tr class="green">
            <td colspan="{{ $fixedColspan }}" style="text-align:right;">TOTAL WASTE</td>
            @foreach ($typeProducts as $tp)
                <td class="right">{{ number_format($saldo[$tp], 2) }}</td>
                <td class="center">KG</td>
            @endforeach
        </tr>
    </table>
</body>

</html>
