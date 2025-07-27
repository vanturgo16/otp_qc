@php
    $data = $data ?? null;
@endphp
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Permintaan Sample</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #000; padding: 4px; text-align: center; }
        .no-border { border: none !important; }
        .header { font-size: 18px; font-weight: bold; }
        .subheader { font-size: 22px; font-weight: bold; }
        .rev { font-size: 12px; }
        .label { text-align: left; width: 150px; }
        .colon { width: 10px; }
        .sign { height: 60px; }
    </style>
</head>
<body>
    <table border="1" cellspacing="0" cellpadding="4" style="width: 100%; border-collapse: collapse;">
    <tr>
        <td style="width: 20%; height: 60px;">
        <img src="{{ public_path('assets/images/icon-otp.png') }}" style="width: 100px; height: auto;">
        </td>
        <td colspan="2" style="text-align: center;">
        <div style="font-size: 14px;">FORM</div>
        <div style="background-color: #ccc; font-weight: bold; font-size: 18px;">SURAT PERMINTAAN SAMPLE</div>
        <div style="font-size: 12px;">FM-SM-QD-08, Rev. 1, &nbsp;01 Juli 2022</div>
        </td>
    </tr>
    </table>
    <table class="no-border" style="width:100%;">
        <tr class="no-border"><td colspan="6" class="no-border">&nbsp;</td></tr>
        <tr class="no-border">
            <td class="label no-border" style="padding-right:0;">No. Sample</td>
            <td class="colon no-border" style="width:10px; padding-left:0; padding-right:2px;">:</td>
            <td class="no-border" style="text-align:left; padding-left:0;">{{ $data->no_sample ?? '' }}</td>
        </tr>
        <tr class="no-border">
            <td class="label no-border" style="padding-right:0;">Tanggal Permintaan</td>
            <td class="colon no-border" style="width:10px; padding-left:0; padding-right:2px;">:</td>
            <td class="no-border" style="text-align:left; padding-left:0;">{{ $data->request_date ?? '' }}</td>
        </tr>
        <tr class="no-border"><td colspan="6" class="no-border">&nbsp;</td></tr>
        <tr class="no-border">
            <td class="label no-border" style="padding-right:0;">Tanggal Jadi Sample</td>
            <td class="colon no-border" style="width:10px; padding-left:0; padding-right:2px;">:</td>
            <td class="no-border" style="text-align:left; padding-left:0;">{{ $data->sample_done_date ?? '' }}</td>
        </tr>
        <tr class="no-border">
            <td class="label no-border" style="padding-right:0;">Tanggal Pengiriman</td>
            <td class="colon no-border" style="width:10px; padding-left:0; padding-right:2px;">:</td>
            <td class="no-border" style="text-align:left; padding-left:0;">{{ $data->sample_submission_date ?? '' }}</td>
        </tr>
        <tr class="no-border">
            <td class="label no-border" style="padding-right:0;">Customer</td>
            <td class="colon no-border" style="width:10px; padding-left:0; padding-right:2px;">:</td>
            <td class="no-border" style="text-align:left; padding-left:0;">{{ $data->customer_name ?? '' }}</td>
        </tr>
        <tr class="no-border">
            <td class="label no-border" style="padding-right:0;">Marketing</td>
            <td class="colon no-border" style="width:10px; padding-left:0; padding-right:2px;">:</td>
            <td class="no-border" style="text-align:left; padding-left:0;">{{ $data->sales_name ?? '' }}</td>
        </tr>
        <tr class="no-border"><td colspan="6" class="no-border">&nbsp;</td></tr>
    </table>
    <table>
        <tr style="font-weight: bold;">
            <td>No</td>
            <td>Bahan</td>
            <td>Ukuran</td>
            <td>Proses</td>
            <td>Jumlah</td>
            <td>No. Lot</td>
        </tr>
        <tr style="height: 30px;">
            <td>1</td>
            <td>{{ $data->product_code ?? '' }}</td>
            <td>{{ $data->description ?? '' }}</td>
            <td></td>
            <td>{{ $data->qty ?? '' }}</td>
            <td>{{ $data->all_barcodes ?? '' }}</td>
        </tr>
    </table>
    <table class="no-border" style="margin-top: 20px;">
        <tr class="no-border">
            <td class="no-border" style="text-align: center;">Diminta Oleh,</td>
            <td class="no-border" style="text-align: center;">Diterima Oleh,</td>
            <td class="no-border" style="text-align: center;">Dikerjakan Oleh,</td>
            <td class="no-border" style="text-align: center;">Diketahui Oleh,</td>
        </tr>
        <tr class="sign no-border"><td class="no-border"></td><td class="no-border"></td><td class="no-border"></td><td class="no-border"></td></tr>
        <tr class="sign no-border"><td class="no-border"></td><td class="no-border"></td><td class="no-border"></td><td class="no-border"></td></tr>
        <tr class="sign no-border"><td class="no-border"></td><td class="no-border"></td><td class="no-border"></td><td class="no-border"></td></tr>
        <tr class="sign no-border"><td class="no-border"></td><td class="no-border"></td><td class="no-border"></td><td class="no-border"></td></tr>
        <tr class="sign no-border"><td class="no-border"></td><td class="no-border"></td><td class="no-border"></td><td class="no-border"></td></tr>
        <tr class="sign no-border"><td class="no-border"></td><td class="no-border"></td><td class="no-border"></td><td class="no-border"></td></tr>
        <tr class="sign no-border"><td class="no-border"></td><td class="no-border"></td><td class="no-border"></td><td class="no-border"></td></tr>
        <tr class="sign no-border"><td class="no-border"></td><td class="no-border"></td><td class="no-border"></td><td class="no-border"></td></tr>
        <tr class="sign no-border"><td class="no-border"></td><td class="no-border"></td><td class="no-border"></td><td class="no-border"></td></tr>
        <tr class="no-border">
            <td class="no-border" style="text-align: center;">( Q&amp;D )</td>
            <td class="no-border" style="text-align: center;">( PPIC )</td>
            <td class="no-border" style="text-align: center;">(Operator)</td>
            <td class="no-border" style="text-align: center;">( Ka. Produksi )</td>
        </tr>
    </table>
</body>
</html>
