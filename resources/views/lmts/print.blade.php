@php
    // filepath: e:\Projek_Qc\otp_qc\resources\views\lmts\print.blade.php
@endphp
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>LMTS - {{ $data->no_lmts }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        td {
            padding: 4px;
            vertical-align: top;
        }

        .border {
            border: 1px solid #000;
        }

        .no-border {
            border: none !important;
        }

        .title {
            text-align: center;
            font-weight: bold;
            font-size: 16px;
        }

        .rev {
            text-align: center;
            font-size: 11px;
        }

        .line {
            display: block;
            border-bottom: 1px solid #000;
            height: 14px;
            margin-bottom: 4px;
        }

        .label {
            width: 120px;
        }

        .checkbox {
            display: inline-block;
            width: 20px;
            height: 16px;
            border: 1px solid #000;
            margin-right: 6px;
            vertical-align: middle;
            background: #fff;
        }

        .checkbox.checked {
            position: relative;
        }

        .checkbox.checked:after {
            content: 'X';
            color: black;
            font-weight: bold;
            position: absolute;
            top: 0px;
            left: 5px;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <!-- HEADER -->
    <table class="border">
        <tr>
            <td class="border" style="width:20%; text-align:center;">
                <img src="{{ public_path('assets/images/icon-otp.png') }}" style="width:80px;">
            </td>
            <td class="border" style="text-align:center;">
                <div class="title">LAPORAN MATERIAL TESTING & STOCK ( LMTS )</div>
                <div class="rev">FM-QC-LMTS-01, Rev. 1, {{ now()->format('d F Y') }}</div>
            </td>
        </tr>
    </table>

    <!-- IDENTITAS MATERIAL -->
    <table class="border" style="margin-top:5px;">
        <tr>
            <td style="width:25%; padding:6px;">Identitas Material :</td>
            <td style="padding:6px;">
                <table style="width:100%;">
                    <tr class="border" style="margin-bottom: 4px;">
                        <td style="width:16%; height:34px; font-weight:bold; font-size:18px;">No. LMTS</td>
                        <td style="border-bottom:1px solid #000; font-weight:bold; font-size:18px;">
                            :{{ $data->no_lmts ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal</td>
                        <td style="border-bottom:1px solid #000;">:
                            <span>{{ \Carbon\Carbon::parse($data->date)->format('d-m-Y') ?? '-' }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>Supplier</td>
                        <td style="border-bottom:1px solid #000;">: <span>{{ $data->supplier_name ?? '-' }}</span></td>
                    </tr>
                    <tr>
                        <td>Jenis Material</td>
                        <td style="border-bottom:1px solid #000;">: <span>{{ $data->type_product ?? '-' }}</span>
                        </td>
                    </tr>

                    <tr>
                        <td>Ukuran</td>
                        <td style="border-bottom:1px solid #000;">: <span>{{ $data->description ?? '-' }}</span></td>
                    </tr>
                    <tr>
                        <td>Quantity</td>
                        <td style="border-bottom:1px solid #000;">:
                            <span>{{ number_format($data->qty, 2) ?? '-' }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>No. Lot/Batch</td>
                        <td style="border-bottom:1px solid #000;">: <span>{{ $data->lot_number ?? '-' }}</span></td>
                    </tr>
                    <tr>
                        <td>Ketidaksesuaian</td>
                        <td style="border-bottom:1px solid #000;">: <span>{{ $data->lmts_notes ?? '-' }}</span>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>

    </table>


    <!-- DISPOSISI -->
    <table class="border" style="margin-top:5px; width:100%;">

        <tr>
            <td class="no-border" style=" vertical-align:top; ">
                Disposisi :<br>
                <div style="margin-top:6px;">
                    <span class="checkbox {{ ($data->status ?? '') == '3' ? 'checked' : '' }}"></span> Repair (
                    Perbaikan )<br>
                    <span class="checkbox {{ ($data->status ?? '') == '2' ? 'checked' : '' }}"></span> Return (
                    Kembalikan ke Supplier )<br>
                    <span class="checkbox {{ ($data->status ?? '') == '1' ? 'checked' : '' }}"></span> Scrap (
                    Buang/Hancurkan )<br>
                    <span class="checkbox"></span> ....................................
                </div>
            </td>


            <td class="no-border" style="text-align:center; width:30%;">
                Disetujui Oleh,<br><br><br><br><br>
                ( <span style="display:inline-block; width:80px; "> QC Manager </span> )
            </td>
            <td class="no-border" style="text-align:center; width:30%;">
                Diposisi,<br><br><br><br><br>
                ( <span style="display:inline-block; width:80px; "> </span> )
            </td>
        </tr>
    </table>

    {{-- Pelaksanaan disposisi --}}
    <table class="border" style="margin-top:5px; width:100%;">

        <tr>
            <td class="no-border" style=" vertical-align:top;">
                Pelaksanaan Disposisi:<br>
                <div style="margin-top:6px;">
                    @if ($data->status == '3')
                        Barang telah diperbaiki dan dikembalikan ke inventory pada
                        {{ \Carbon\Carbon::parse($data->updated_at)->format('d-m-Y') }}
                    @elseif ($data->status == '2')
                        Barang telah dikembalikan ke supplier {{ $data->supplier_name ?? '' }} pada
                        {{ \Carbon\Carbon::parse($data->updated_at)->format('d-m-Y') }}
                    @elseif ($data->status == '1')
                        Barang telah di-scrap/dibuang pada
                        {{ \Carbon\Carbon::parse($data->updated_at)->format('d-m-Y') }}
                    @else
                        <div class="line"></div>
                        <div class="line"></div>
                    @endif
                    <div class="line"></div>
                    <div class="line"></div>
                </div>



            <td class="no-border" style="text-align:center; width:30%;">
                Dilaksanakan Oleh,<br><br><br><br><br>
                ( <span style="display:inline-block; width:80px; "> Warehouse </span> )
            </td>
        </tr>
    </table>

    {{-- Verifikasi disposisi --}}
    <table class="border" style="margin-top:5px; width:100%;">

        <tr>
            <td class="no-border" style=" vertical-align:top;">
                Verifikasi Hasil Pelaksanaan Disposisi:<br>
                <div style="margin-top:6px;">
                    <div class="line"></div>
                    <div class="line"></div>
                    <div class="line"></div>
                    <div class="line"></div>
                </div>



            <td class="no-border" style="text-align:center; width:30%;">
                Diverifikasi Oleh,<br><br><br><br><br>
                ( <span style="display:inline-block; width:80px; "> QC Manager </span> )
            </td>
        </tr>
    </table>

    <!-- Distribusi -->
    <table class="border" style="margin-top:5px; width:100%;">

        <tr>
            <td class="no-border" style=" vertical-align:top; ">
                Distribusi :<br>
                <div style="margin-top:6px;">
                    <span class="checkbox"></span> Purchasing <br>
                    <span class="checkbox"></span> Warehouse<br>
                    <span class="checkbox"></span> QC<br>
                    <span class="checkbox"></span> Management<br>

                </div>
            </td>



        </tr>
    </table>
</body>

</html>
