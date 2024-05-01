<?php

namespace App\Services\Admin\Stock;

use App\Domain\Entities\Stock;
use App\Utils\CsvUtil;

class DownloadCsvService extends BaseStockService
{
    /**
     * @return string
     */
    public function getCsvData(): string
    {
        $stocks = $this->searchStock(0);

        $headers = ['ID', '商品名', '価格'];
        $rows = [];
        foreach ($stocks as $stock) {
            if (!$stock instanceof Stock) {
                throw new \RuntimeException('An unexpected error occurred.');
            }
            $row = [];
            $row[] = $stock->id;
            $row[] = $stock->name;
            $row[] = $stock->price;
            $rows[] = $row;
        }

        return CsvUtil::make($rows, $headers);
    }
}
