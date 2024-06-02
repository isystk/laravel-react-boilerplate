<?php

namespace App\Services\Admin\Stock;

use App\Domain\Entities\Stock;
use App\Domain\Repositories\Stock\StockRepository;
use App\Utils\CsvUtil;

class DownloadCsvService extends BaseStockService
{

    private StockRepository $stockRepository;

    /**
     * Create a new controller instance.
     *
     * @param StockRepository $stockRepository
     */
    public function __construct(
        StockRepository $stockRepository
    )
    {
        $this->stockRepository = $stockRepository;
    }

    /**
     * 商品情報のCSVデータを取得します。
     * @param array{
     *   name : ?string,
     *   sort_name : string,
     *   sort_direction : 'asc' | 'desc',
     *   limit : int,
     * } $conditions
     * @return string
     */
    public function getCsvData(array $conditions): string
    {
        $headers = $this->getHeader();
        $rows = $this->getDetail($conditions);

        return CsvUtil::make($rows, $headers);
    }

    /**
     * ヘッダーを取得します。
     * @return array<string>
     */
    private function getHeader(): array
    {
        return [
            'ID',
            '商品名',
            '価格'
        ];
    }

    /**
     * 詳細データを取得します。
     * @param array{
     *   name : ?string,
     *   sort_name : string,
     *   sort_direction : 'asc' | 'desc',
     *   limit : int,
     * } $conditions
     * @return array<array<string>>>
     */
    private function getDetail(array $conditions): array
    {
        $stocks = $this->stockRepository->getByConditions($conditions);
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
        return $rows;
    }
}
