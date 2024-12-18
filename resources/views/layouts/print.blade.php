<!-- resources/views/layouts/print.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Packing List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .page-content {
            margin: 20px;
        }

        .text-center {
            text-align: center;
        }

        .float-left {
            float: left;
        }

        .float-right {
            float: right;
        }

        .clear {
            clear: both;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th,
        .table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        .table th {
            background-color: #f2f2f2;
        }

        .header-row {
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>

<body>
    @yield('content')
</body>

</html>