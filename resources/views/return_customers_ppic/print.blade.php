<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Penerimaan Barang Retur</title>
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

        .no-border {
            border: none !important;
        }

        .header {
            font-size: 18px;
            font-weight: bold;
        }

        .subheader {
            font-size: 16px;
            font-weight: bold;
            background: #ccc;
        }

        .rev {
            font-size: 12px;
        }

        .label {
            text-align: left;
            width: 100px;
            border: none;
        }

        .colon {
            width: 10px;
            border: none;
        }

        .sign {
            height: 40px;
        }

        .checkbox {
            display: inline-block;
            width: 30px;
            height: 24px;
            border: 1px solid #000;
            margin-right: 6px;
            vertical-align: middle;
            background: #fff;
        }
    </style>
</head>

<body>
    <!-- HEADER -->
    <table style="width:100%; border:1px solid #000;">
        <tr>
            <td style="width: 15%; border-right:none; border-top:none; border-left:none;">
                <img src="{{ public_path('assets/images/icon-otp.png') }}" style="width: 90px;">
            </td>
            <td style="border:none; text-align:center;">
                <div class="header">LAPORAN</div>
                <div class="subheader">RETURN CUSTOMER</div>
                <div class="rev">FM-SM-PPIC-19, Rev. 0, 01 September 2021</div>
            </td>
        </tr>
    </table>

    <!-- IDENTITAS -->
    <table class="no-border" style="margin-top:8px; width:100%;">
        <tr>
            <td class="label">TANGGAL</td>
            <td class="colon">:</td>
            <td class="no-border">{{ $tanggal ?? '' }}</td>
        </tr>
        <tr>
            <td class="label">CUSTOMER</td>
            <td class="colon">:</td>
            <td class="no-border">{{ $customer ?? '' }}</td>
        </tr>
    </table>

    <!-- TABEL UTAMA -->
    <table style="margin-top:8px; width:100%;">
        <tr style="font-weight:bold; text-align:center;">
            <th style="width:30px;">NO</th>
            <th colspan="2">NAMA BARANG</th>
            <th>NO. SURAT JALAN</th>
            <th>NO. PO</th>
            <th style="width:60px;">JUMLAH</th>
            <th style="width:40px;">Unit</th>
            <th style="width:60px;">BERAT</th>
            <th>KETERANGAN</th>
            <th>TINDAKAN</th>
        </tr>
        @foreach ($datas as $i => $data)
            <tr>
                <td style="text-align:center;">{{ $i + 1 }}</td>
                <td style="text-align:left;" colspan="2">{{ $data->name ?? '' }}</td>
                <td style="text-align:left;">{{ $data->dn_number ?? '' }}</td>
                <td style="text-align:left;">{{ $data->no_po ?? '' }}</td>
                <td style="text-align:center;">{{ $data->qty ?? '' }}</td>
                <td style="text-align:center;">{{ $data->unit ?? '' }}</td>
                <td style="text-align:center;">{{ $data->weight ?? '' }}</td>
                <td style="text-align:left;">{{ $data->keterangan ?? '' }}</td>
                <td style="text-align:left;">{{ $data->qc_status ?? '' }}</td>
            </tr>
        @endforeach

    </table>

    <!-- DISTRIBUSI & SIGN -->
    <table style="width:100%; margin-top:8px; border:none;">
        <tr>
            <td style="width:35%; border:none; vertical-align:top; ">
                <span style="font-size:16px; font-weight:bold;">Distribusi</span> :<br>
                <div style="margin-top:8px;">
                    <span class="checkbox"></span> Q&D<br>
                    <span class="checkbox"></span> PPIC<br>
                    <span class="checkbox"></span> PRODUKSI<br>
                    <span class="checkbox"></span> MARKETING
                </div>

            <td style="width:10%; border:none;"></td>
            <td style="width:55%; border:none;">
                <table style="width:100%;">
                    <tr>
                        <td style="border:1px solid #000; text-align:center;">Dibuat Oleh</td>
                        <td style="border:1px solid #000; text-align:center;">Dicek Oleh</td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #000; height:51px;"></td>
                        <td style="border:1px solid #000; height:51px;"></td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #000; text-align:center;">PPIC</td>
                        <td style="border:1px solid #000; text-align:center;">Q&D</td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #000; text-align:left;">Tgl :</td>
                        <td style="border:1px solid #000; text-align:left;">Tgl :</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
