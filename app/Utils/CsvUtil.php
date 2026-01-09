<?php

namespace App\Utils;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Response;

class CsvUtil
{
    /**
     * CSVファイルの生成
     *
     * @param array<array<string|int>> $rows
     * @param array<string>            $headers
     */
    public static function make(array $rows, array $headers): string
    {
        if (count($headers) > 0) {
            array_unshift($rows, $headers);
        }
        $stream = fopen('php://temp', 'r+b');
        foreach ($rows as $row) {
            fputcsv($stream, $row);
        }
        rewind($stream);
        $csv = str_replace(PHP_EOL, "\r\n", stream_get_contents($stream));
        $csv = "\xEF\xBB\xBF" . $csv; // BOM 追加

        return $csv;
    }

    /**
     * CSVダウンロード
     *
     * @param array<int, array<int, mixed>> $list
     * @param array<string>                 $header
     *
     * @throws BindingResolutionException
     */
    public static function download(array $list, array $header, string $filename): Response
    {
        $csv = self::make($list, $header);

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
        ];

        return response()->make($csv, 200, $headers);
    }
}
