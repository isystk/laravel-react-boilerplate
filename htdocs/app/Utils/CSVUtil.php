<?php

namespace App\Utils;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Response;

class CSVUtil
{

    /**
     * CSVファイルの生成
     * @param array<int, array<int, mixed>> $list
     * @param array<string> $header
     * @return string
     */
    public static function make(array $list, array $header): string
    {
        if (count($header) > 0) {
            array_unshift($list, $header);
        }
        $stream = fopen('php://temp', 'r+b');
        foreach ($list as $row) {
            fputcsv($stream, $row);
        }
        rewind($stream);
        $csv = str_replace(PHP_EOL, "\r\n", stream_get_contents($stream));
        $csv = mb_convert_encoding($csv, 'SJIS-win', 'UTF-8');

        return $csv;
    }

    /**
     * CSVダウンロード
     * @param array<int, array<int, mixed>> $list
     * @param array<string> $header
     * @param string $filename
     * @return Response
     * @throws BindingResolutionException
     */
    public static function download(array $list, array $header, string $filename): Response
    {
        $csv = self::make($list, $header);

        $headers = array(
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
        );
        return response()->make($csv, 200, $headers);
    }
}
