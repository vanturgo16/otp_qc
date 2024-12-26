@extends('layouts.master')

@section('konten')
<div class="page-content">
    <div class="container-fluid">
        <button onclick="window.print()" class="btn btn-primary no-print">Print</button>
        <div class="barcode-print">
            @foreach ($barcodeDetails as $barcode)
            <div class="barcode-item">
                <div class="title-box">
                    <strong>POF SHRINK FILM</strong>
                </div>
                <div class="subtitle">
                    <strong>COUNTRY OF INDONESIA</strong>
                </div>
                <div class="company-left">
                    CBC AMERICA LLC
                </div>
                <table class="barcode-table">
                    <tr>
                        <td class="label"><strong>SO No.</strong></td>
                        <td class="colon">:</td>
                        <td class="value">{{ $barcode->so_number }}
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <span style="float:">{{ \Carbon\Carbon::parse($barcode->tgl_buat)->format('d M Y') }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="label"><strong>PO No.</strong></td>
                        <td class="colon">:</td>
                        <td class="value">{{ $barcode->po_number ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="label"><strong>Desc</strong></td>
                        <td class="colon">:</td>
                        <td class="value">{{ $barcode->description ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="label"><strong>Size</strong></td>
                        <td class="colon">:</td>
                        <td class="value">{{ $barcode->width ?? 'N/A' }} &nbsp;&nbsp;&nbsp;&nbsp; <strong>P:</strong>{{ $barcode->perforasi ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="label"><strong>Thickness</strong></td>
                        <td class="colon">:</td>
                        <td class="value">{{ $barcode->thickness ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="label"><strong>Group</strong></td>
                        <td class="colon">:</td>
                        <td class="value">{{ $barcode->shift }} &nbsp; <strong>Machine: {{ $barcode->work_center_code }}</strong> &nbsp; <strong>Joint:</strong> <span class="joint">1</span> <span class="joint">2</span> <span class="joint">3</span></td>
                    </tr>
                    <tr>
                        <td class="label"><strong>Lot</strong></td>
                        <td class="colon">:</td>
                        <td class="value">
                            <img class="barcode-img" src="data:image/png;base64,{{ DNS1D::getBarcodePNG($barcode->barcode_number, 'C128') }}" alt="barcode" />
                            <div class="barcode-number">{{ $barcode->barcode_number }}</div>
                        </td>
                    </tr>
                </table>
                <hr class="thick-line">
                <div class="storage-info">
                    <div class="storage-temp">
                        Storage Temperature : Max 35 °C
                    </div>
                    <div class="storage-warning">
                        <strong>Avoid high temperature. High humidity and direct sunlight during storage</strong>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<style>
.page-content {
    max-width: 700px;
    padding: 50px;
}

.barcode-item {
    margin-bottom: 10px;
    page-break-inside: avoid; /* Menghindari pemutusan halaman di dalam satu item barcode */
}

.barcode-table {
    width: 100%;
    border-collapse: collapse;
}

.barcode-table td {
    padding: 3px;
    vertical-align: top;
}

.label {
    text-align: left;
    white-space: nowrap;
    padding-right: 10px;
}

.colon {
    width: 10px;
    text-align: center;
}

.value {
    text-align: left;
    white-space: nowrap;
}

.company-name {
    text-align: left;
    font-weight: bold;
    font-size: 20px;
    padding-bottom: 10px;
}

.up-down-table {
    border-collapse: collapse;
}

.up-down-table td {
    width: 23px;
    height: 17px;
    text-align: center;
    line-height: 17px;
    border: 1px solid #000;
}

.barcode-container {
    text-align: left;
}

.barcode-img {
    display: block;
    margin: 3px 0 0 0; /* Menghilangkan jarak bawah */
    width: 250px; /* Lebar diperbesar */
    height: 60px; /* Tinggi diperbesar */
}

.barcode-number {
    text-align: left;
    margin-left: 80px;
}

.joint {
    display: inline-block;
    width: 20px;
    height: 20px;
    border: 1px solid #000;
    text-align: center;
    line-height: 20px;
    margin-left: 2px;
}

/* Kelas untuk elemen yang tidak ingin dicetak */
.no-print {
    display: none;
}

.title-box {
    display: inline-block;
    border: 1px solid black;
    padding: 10px 20px;
    margin: 20px 0;
    font-size: 24px;
    text-align: center;
    width: 100%;
    font-weight: bold;
}

.subtitle {
    font-size: 18px;
    margin: 10px 0;
    text-align: center;
    font-weight: bold;
}

.company-left {
    font-size: 16px;
    font-weight: bold;
    text-align: left;
    margin: 10px 0;
}

.storage-info {
    margin-top: 20px;
}

.storage-temp {
    font-size: 16px;
    text-align: left;
}

.storage-warning {
    font-size: 16px;
    font-weight: bold;
    text-align: left;
    margin-top: 5px;
}

.thick-line {
    border: 2px solid black;
    margin-top: 20px;
}

@media print {
    .page-content {
        margin: 0;
        padding: 0;
        width: 100%;
    }

    .barcode-item {
        page-break-inside: avoid; /* Pastikan barcode tidak terpisah ke halaman berikutnya */
    }

    .no-print {
        display: none; /* Sembunyikan elemen dengan kelas no-print saat mode cetak */
    }
}
</style>
@endsection
