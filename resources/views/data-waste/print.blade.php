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
    <table style="margin-top:8px; width:100%;">
        <tr style="font-weight:bold; text-align:center;">
            <th rowspan="2">TANGGAL</th>
            <th rowspan="2">Type product</th>
            <th rowspan="2">work Center</th>
            <th colspan="5">IN</th>
            <th colspan="4">OUT</th>
            @if ($selectedType == 'POF' || !$selectedType)
                <th colspan="2">SALDO POF</th>
            @endif
            @if ($selectedType == 'PP' || !$selectedType)
                <th colspan="2">SALDO PP</th>
            @endif
            @if ($selectedType == 'Crosslink' || !$selectedType)
                <th colspan="2">SALDO Crosslink</th>
            @endif
        </tr>
        <tr style="font-weight:bold; text-align:center;">
            <th>Weight</th>
            <th>Unit</th>
            <th>Status</th>
            <th>Keterangan</th>
            <th></th>
            <th>Report Number</th>
            <th>Weight</th>
            <th>Unit</th>
            <th>Status</th>
            <th>Keterangan</th>
            @if ($selectedType == 'POF' || !$selectedType)
                <th>Weight</th>
                <th>Unit</th>
            @endif
            @if ($selectedType == 'PP' || !$selectedType)
                <th>Weight</th>
                <th>Unit</th>
            @endif
            @if ($selectedType == 'Crosslink' || !$selectedType)
                <th>Weight</th>
                <th>Unit</th>
            @endif
        </tr>
        @php
            $saldo = ['POF' => 0, 'PP' => 0, 'Crosslink' => 0];
        @endphp
        @foreach ($datas as $row)
            @php
                // Langsung ambil dari data
                $in_weight = $row->weight;
                $in_unit = $row->unit;
                $in_status = $row->status;
                $in_keterangan = $row->keterangan ?? ($row->remark ?? '');
                $out_report = $row->report_number ?? ($row->no_report ?? '');
                $tanggal = $row->tanggal ?? ($row->waste_date ?? $row->created_at);

                // Saldo
                if ($row->type_product == 'POF') {
                    $saldo['POF'] += floatval($in_weight);
                }
                if ($row->type_product == 'PP') {
                    $saldo['PP'] += floatval($in_weight);
                }
                if ($row->type_product == 'Crosslink') {
                    $saldo['Crosslink'] += floatval($in_weight);
                }
            @endphp
            <tr>
                <td class="center">{{ \Carbon\Carbon::parse($tanggal)->format('m/d/Y') }}</td>
                <td class="center">{{ $row->type_product }}</td>
                <td class="center">{{ $row->work_center }}</td>
                <td class="right">{{ $in_weight }}</td>
                <td class="center">{{ $in_unit }}</td>
                <td class="center">{{ $in_status }}</td>
                <td class="center">{{ $in_keterangan }}</td>
                <td class="center">{{ $out_report }}</td>
                @if ($selectedType == 'POF' || !$selectedType)
                    <td class="right">{{ $row->type_product == 'POF' ? number_format($saldo['POF'], 2) : '' }}</td>
                    <td class="center">{{ $row->type_product == 'POF' ? 'Kg' : '' }}</td>
                @endif
                @if ($selectedType == 'PP' || !$selectedType)
                    <td class="right">{{ $row->type_product == 'PP' ? number_format($saldo['PP'], 2) : '' }}</td>
                    <td class="center">{{ $row->type_product == 'PP' ? 'Kg' : '' }}</td>
                @endif
                @if ($selectedType == 'Crosslink' || !$selectedType)
                    <td class="right">
                        {{ $row->type_product == 'Crosslink' ? number_format($saldo['Crosslink'], 2) : '' }}</td>
                    <td class="center">{{ $row->type_product == 'Crosslink' ? 'Kg' : '' }}</td>
                @endif
            </tr>
        @endforeach
        <!-- Baris kosong jika data kurang dari 4 -->
        @for ($i = count($datas); $i < 4; $i++)
            <tr>
                <td colspan="13"></td>
                @if ($selectedType == 'POF' || !$selectedType)
                    <td></td>
                    <td></td>
                @endif
                @if ($selectedType == 'PP' || !$selectedType)
                    <td></td>
                    <td></td>
                @endif
                @if ($selectedType == 'Crosslink' || !$selectedType)
                    <td></td>
                    <td></td>
                @endif
            </tr>
        @endfor
        <!-- Total Waste -->
        <tr class="green">
            <td colspan="13" style="text-align:right;">TOTAL WASTE</td>
            @if ($selectedType == 'POF' || !$selectedType)
                <td class="right">{{ number_format($saldo['POF'], 2) }}</td>
                <td class="center">KG</td>
            @endif
            @if ($selectedType == 'PP' || !$selectedType)
                <td class="right">{{ number_format($saldo['PP'], 2) }}</td>
                <td class="center">KG</td>
            @endif
            @if ($selectedType == 'Crosslink' || !$selectedType)
                <td class="right">{{ number_format($saldo['Crosslink'], 2) }}</td>
                <td class="center">KG</td>
            @endif
        </tr>
    </table>
</body>

</html>
