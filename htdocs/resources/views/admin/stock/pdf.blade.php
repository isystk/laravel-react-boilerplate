<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>PDF</title>
    <style>
        @font-face {
            font-family: ipag;
            font-style: normal;
            font-weight: normal;
            src: url("{{ storage_path('fonts/IPAfont00303/ipag.ttf') }}") format('truetype');
        }

        @font-face {
            font-family: ipag;
            font-style: bold;
            font-weight: bold;
            src: url("{{ storage_path('fonts/IPAfont00303/ipag.ttf') }}") format('truetype');
        }

        body {
            font-family: ipag !important;
        }

        table th, table td {
            padding: 10px 0;
            text-align: center;
        }

        table th {
            background-color: #17a2b8;
        }

        table tr:nth-child(odd) {
            background-color: #eee;
        }

        thead {
            display: table-header-group;
        }

        tfoot {
            display: table-row-group;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            word-break: break-all;
            word-wrap: break-word;
        }

        tr {
            page-break-inside: avoid;
        }
    </style>
</head>
<body>
<p>{{ __('stock.Stock List') }}</p>
<table>
    <thead>
    <tr>
        @foreach ($headers as $header)
            <th>{{ $header }}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach ($rows as $row)
        <tr>
            @foreach ($row as $item)
                <td>{{ $item }}</td>
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
