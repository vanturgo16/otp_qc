<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>LPTS</title>
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
            top:0px;
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
                <div class="title">LAPORAN PRODUK TIDAK SESUAI ( LPTS )</div>
                <div class="rev">FM-SM-QD-02, Rev. 1, 01 Juli 2022</div>
            </td>
        </tr>
    </table>

    <!-- IDENTITAS PRODUK -->
    <table class="border" style="margin-top:5px;">
        <tr>
            <td style="width:25%; padding:6px;">Identitas Produk :</td>
            <td style="padding:6px;">
                <table style="width:100%;">
                    <tr class="border" style="margin-bottom: 4px;">
                        <td style="width:16%; height:34px; font-weight:bold; font-size:18px;">No. Urut</td>
                        <td style="border-bottom:1px solid #000; font-weight:bold; font-size:18px;">
                            :{{ $data->no_lpts ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal</td>
                        <td style="border-bottom:1px solid #000;">:
                            <span>{{ $data->created_at_formatted ?? '-' }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>Nama Produk</td>
                        <td style="border-bottom:1px solid #000;">: <span>{{ $data->product_code ?? '-' }}</span></td>
                    </tr>
                    <tr>
                        <td>No. WO</td>
                        <td style="border-bottom:1px solid #000;">: <span>{{ $data->wo_number ?? '-' }}</span></td>
                    </tr>
                    <tr>
                        <td>No. Roll</td>
                        <td style="border-bottom:1px solid #000;">: <span>{{ $data->barcode_numbers ?? '-' }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>Diproses</td>
                        <td style="border-bottom:1px solid #000;">: <span>{{ $data->packing_number ?? '-' }}</span></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="padding:6px; border-top:1px solid #000;">Ketidaksesuaian</td>
            <td style="padding:6px; border-top:1px solid #000;">
                @if ($data->keterangan)
                    :<span style="margin-left: 3px">{{ $data->keterangan }}</span>
                @else
                    <div class="line"></div>
                    <div class="line"></div>
                    <div class="line"></div>
                @endif

            </td>
        </tr>
    </table>

    <!-- UKURAN -->
    <table class="border" style="margin-top:5px;  width:100%;">
        <tr>

            <td style="padding: 6px;">
                <table>
                    <tr>
                        <td class="label no-border">Ukuran</td>
                        <td style="border-bottom:1px solid #000;"><span>{{ $data->description ?? '-' }}</span></td>
                    </tr>
                    <tr>
                        <td class="no-border">Jumlah</td>
                        <td style="border-bottom:1px solid #000;"><span>{{ $data->qty ?? '-' }}</span></td>
                    </tr>
                    <tr>
                        <td class="no-border">Total Order</td>
                        <td style="border-bottom:1px solid #000;"><span>{{ $data->qty_needed ?? '-' }}</span></td>
                    </tr>
                    <tr>
                        <td>% Ketidaksesuaian</td>
                        <td style="border-bottom:1px solid #000;">&nbsp;</td>
                    </tr>
                </table>
            </td>

            <td class="no-border" style="text-align:center; width:30%;">
                Dilaporkan,<br><br><br><br><br>
                ( <span style="display:inline-block; width:80px; "> QC </span> )
            </td>
        </tr>
    </table>

    <!-- DISPOSISI -->
    <table class="border" style="margin-top:5px; width:100%;">

        <tr>
            <td class="no-border" style=" vertical-align:top; ">
                Disposisi :<br>
                <div style="margin-top:6px;">
                    <span class="checkbox"></span> Repair ( Perbaikan )<br>
                    <span class="checkbox {{ ($data->qc_status ?? '') === 'rework' ? 'checked' : '' }}"></span> Rework ( Produksi Ulang )<br>
                    <span class="checkbox {{ ($data->qc_status ?? '') === 'scrap' ? 'checked' : '' }}"></span> Scrap ( Hancurkan )<br>
                    <span class="checkbox"></span> ....................................
                </div>
            </td>


            <td class="no-border" style="text-align:center; width:30%;">
                Dilaporkan,<br><br><br><br><br>
                ( <span style="display:inline-block; width:80px; "> </span> )
            </td>
            <td class="no-border" style="text-align:center; width:30%;">
                Dilaporkan,<br><br><br><br><br>
                ( <span style="display:inline-block; width:80px; "> </span> )
            </td>
        </tr>
    </table>

    {{-- Pelaksanaan disposisi --}}
    <table class="border" style="margin-top:5px; width:100%;">

        <tr>
            <td class="no-border" style=" vertical-align:top;">
                pelaksanaan Disposisi:<br>
                <div style="margin-top:6px;">
                    <div class="line"></div>
                    <div class="line"></div>
                    <div class="line"></div>
                    <div class="line"></div>
                </div>



            <td class="no-border" style="text-align:center; width:30%;">
                Dilaporkan,<br><br><br><br><br>
                ( <span style="display:inline-block; width:80px; "> </span> )
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
                Dilaporkan,<br><br><br><br><br>
                ( <span style="display:inline-block; width:80px; "> </span> )
            </td>
        </tr>
    </table>

    <!-- Distribusi -->
    <table class="border" style="margin-top:5px; width:100%;">

        <tr>
            <td class="no-border" style=" vertical-align:top; ">
                Distribusi :<br>
                <div style="margin-top:6px;">
                    <span class="checkbox"></span> PPIC <br>
                    <span class="checkbox"></span> MKT<br>
                    <span class="checkbox"></span> PROD<br>

                </div>
            </td>



        </tr>
    </table>
</body>

</html>
