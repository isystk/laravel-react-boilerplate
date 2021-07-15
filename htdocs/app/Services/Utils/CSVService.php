<?php

namespace App\Services\Utils;

class CSVService
{

  /**
   * CSVファイルの生成
   * @param array $list
   * @param array $header
   * @return string
   */
  public static function make($list, $header)
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
   * @param array $list
   * @param array $header
   * @param string $filename
   * @return \Illuminate\Http\Response
   */
  public static function download($list, $header, $filename)
  {
    $csv = CSVService::make($list, $header);

    $headers = array(
      'Content-Type' => 'text/csv',
      'Content-Disposition' => "attachment; filename=$filename",
    );
    return response()->make($csv, 200, $headers);
  }
}
