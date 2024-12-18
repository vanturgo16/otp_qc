@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <button onclick="window.print()" class="btn btn-primary no-print">Print</button>
        <div class="barcode-print">
          
            <div class="barcode-item">
              
<table class="barcode-table">
    <tr><b>
        <td class="label"><strong>SO No.</strong></td>
        <td class="colon">:</td>
        <td class="value">{{ $barcode->so_number }}
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <span style="float:">{{ \Carbon\Carbon::now()->format('d F Y') }}</span>
        </td>
    </b>
    </tr>
    <tr>
        <td colspan="3" class="company-name">PT Olefina Tifaplas Polikemindo</td>
    </tr>
    
    <tr>
        <td class="label"><strong>Customer</strong></td>
        <td class="colon">:</td>
        <td class="value">{{ $barcode->nm_cust ?? 'N/A' }}</td>
    </tr>
    <tr>
        <td class="label"><strong>Artikel</strong></td>
        <td class="colon">:</td>
        <td class="value">{{ $barcode->description ?? 'N/A' }}</td>
    </tr>
    <tr>
        <td class="label"><strong>Size</strong></td>
        <td class="colon">:</td>
        <td class="value">{{ $barcode->width ?? 'N/A' }} MM  &nbsp; X &nbsp;{{ $barcode->height ?? '' }} M
            &nbsp;&nbsp;&nbsp;&nbsp; <strong>P:</strong>{{ $barcode->perforasi ?? 'N/A' }}</td>
    </tr>
    <tr>
        <td class="label"><strong>Thickness</strong></td>
        <td class="colon">:</td>
        <td class="value">{{ $barcode->thickness ?? 'N/A' }} MIC </td>
    </tr>
    <tr>
        <td class="label"><strong>Group</strong></td>
        <td class="colon">:</td>
        <td class="value">{{ $barcode->shift }} &nbsp; <strong>Machine: {{ $barcode->work_center_code }}</strong> &nbsp; <strong>Joint:</strong> <span class="joint">1</span> <span class="joint">2</span> <span class="joint">3</span></td>
    </tr>
    <tr>
        <td class="label"><strong>Up</strong></td>
        <td class="colon">:</td>
        <td class="value">
            <table class="up-down-table">
                <tr>
                    @for ($i = 1; $i <= 10; $i++)
                    <td>{{ $i % 10 }}</td>
                    @endfor
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td class="label"><strong>Down</strong></td>
        <td class="colon">:</td>
        <td class="value">
            <table class="up-down-table">
                <tr>
                    @for ($i = 1; $i <= 10; $i++)
                    <td>{{ $i % 10 }}</td>
                    @endfor
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td class="label"><strong>Lot</strong></td>
        <td class="colon">:</td>
        <td class="value">
            <dev class="barcode-number">{{ $barcode->barcode_number }}</dev>
            {{-- <img class="barcode-img" src="data:image/png;base64,{{ DNS1D::getBarcodePNG($barcode->barcode_number, 'C128') }}" alt="barcode" /> --}}
           
        </td>
        <tr>
            <td class="label">
                <br>
                <br>
               
               <b> Made In Indonesia</b></td>
            <td></td>
            <td>  
                &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                <img class="qr-code" src="data:image/png;base64,{{ DNS2D::getBarcodePNG($barcode->barcode_number, 'QRCODE', 4, 4) }}" alt="QR Code" />


            </td>
        </tr>
    </tr>
</table>

            </div>
           
        </div>
    </div>
</div>


<style>
.page-content {
    max-width: 700px;
    padding: 40px;
}

.barcode-item {
    margin-bottom: 10px;
    page-break-inside: avoid; /* Menghindari pemutusan halaman di dalam satu item barcode */
}

.barcode-table {
    width: 50%;
    border-collapse: collapse;
}

.barcode-table td {
    padding: 3px;
    vertical-align: top;
}

.label {
    text-align: left;
    white-space: nowrap;
    padding-right: 5px;
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
    padding-bottom: 5px;
}

.up-down-table {
    border-collapse: collapse;
}

.up-down-table td {
    width: 20px;
    height: 15px;
    text-align: center;
    line-height: 15px;
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
    /* margin-top: px; */
    text-align: left;
    /* margin-left: 10px; */
    /* margin-top: 0px; */
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
{{-- <style>
    .kotak-tebal {
        width: 500px; /* Lebar kotak */
        height: 420px; /* Tinggi kotak */
        border: 5px solid black; /* Ketebalan dan warna border */
        /* Pusatkan teks secara horizontal */
        line-height: 15px; /* Pusatkan teks secara vertikal */
        /* padding-left: 10px;  */
        /* padding-right: 12px;  */
        padding-top: 10px; 
        margin-left: 10px; 
        margin-top: 50px; 
    
    }
</style> --}}
@endsection
