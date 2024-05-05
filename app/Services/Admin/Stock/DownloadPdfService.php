<?php

namespace App\Services\Admin\Stock;

use App\Domain\Entities\Stock;

class DownloadPdfService extends BaseStockService
{
    /**
     * @return array{
     *     0: array<string>,
     *     1: array<array<string|int>>
     * }
     */
    public function getPdfData(): array
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
        return [$headers, $rows];
    }

}
