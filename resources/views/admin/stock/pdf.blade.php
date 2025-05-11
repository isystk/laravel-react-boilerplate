@extends('layouts.pdf')
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
