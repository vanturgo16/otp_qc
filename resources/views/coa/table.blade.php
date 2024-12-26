<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Produksi Blow POF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        .header-table td {
            border: none;
        }
        .header-table {
            margin-bottom: 10px;
        }
        .section-title {
            font-weight: bold;
            text-align: left;
            padding: 5px 0;
        }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td rowspan="2" style="width: 20%;"><img src="path_to_logo.png" alt="Logo" width="100"></td>
            <td style="width: 60%; text-align: center; font-weight: bold;">PT OLEFINA TIFAPLAS POLIKEMINDO</td>
            <td rowspan="2" style="width: 20%;"></td>
        </tr>
        <tr>
            <td style="text-align: center; font-weight: bold;">FORM LAPORAN PRODUKSI BLOW POF</td>
        </tr>
        <tr>
            <td colspan="3" style="text-align: center;">FM-SM-PO EXT 02, REV 03, 22 Januari 2018</td>
        </tr>
    </table>

    <div class="section-title">Report Blow</div>
    <table>
        <thead>
            <tr>
                <th rowspan="2">Jam Kerja</th>
                <th colspan="2">Mul</th>
                <th colspan="5">Hasil Produksi</th>
            </tr>
            <tr>
                <th>Sel</th>
                <th>Roll</th>
                <th>μ</th>
                <th>Panjang (Mtr)</th>
                <th>Lebar (Cm)</th>
                <th>Berat (Kg)</th>
                <th>Barcode</th>
                <th>Berat Standar (Kg)</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data rows go here -->
        </tbody>
    </table>

    <div class="section-title">Report Sliting</div>
    <table>
        <thead>
            <tr>
                <th rowspan="2">Jam Kerja</th>
                <th colspan="3">Bahan Awal</th>
                <th colspan="6">Hasil Produksi</th>
                <th colspan="3">Waste Produksi</th>
            </tr>
            <tr>
                <th>Mul</th>
                <th>Sel</th>
                <th>Ukuran</th>
                <th>Kg</th>
                <th>Barcode</th>
                <th>Ukuran</th>
                <th>Kg</th>
                <th>Bagus</th>
                <th>Hold</th>
                <th>Reject</th>
                <th>Barcode</th>
                <th>No. WO</th>
                <th>Kg</th>
                <th>Penyebab Waste</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data rows go here -->
        </tbody>
    </table>

    <div class="section-title">Report Folding</div>
    <table>
        <thead>
            <tr>
                <th rowspan="2">Jam Kerja</th>
                <th colspan="3">Bahan Awal</th>
                <th colspan="6">Hasil Produksi</th>
                <th colspan="3">Waste Produksi</th>
            </tr>
            <tr>
                <th>Mul</th>
                <th>Sel</th>
                <th>Ukuran</th>
                <th>Kg</th>
                <th>Barcode</th>
                <th>Ukuran</th>
                <th>Kg</th>
                <th>Bagus</th>
                <th>Hold</th>
                <th>Reject</th>
                <th>Barcode</th>
                <th>No. WO</th>
                <th>Kg</th>
                <th>Penyebab Waste</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data rows go here -->
        </tbody>
    </table>

    <div class="section-title">Report Bag Making</div>
    <table>
        <thead>
            <tr>
                <th rowspan="2">Jam Kerja</th>
                <th colspan="3">Bahan Awal</th>
                <th colspan="4">Hasil Produksi</th>
            </tr>
            <tr>
                <th>Mul</th>
                <th>Sel</th>
                <th>Jml Roll</th>
                <th>Ukuran</th>
                <th>Kg</th>
                <th>Barcode</th>
                <th>Ukuran</th>
                <th>Jml (Pcs)</th>
                <th>Waste (Kg)</th>
                <th>Barcode</th>
                <th>No. WO</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data rows go here -->
        </tbody>
    </table>
</body>
</html>
