<?php

namespace App\Services\Admin\Stock;

use App\Domain\Entities\Stock;
use App\Domain\Repositories\Stock\StockRepository;

class DownloadPdfService extends BaseStockService
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
     * PDFに出力する商品データを取得します。
     * @param array{
     *   name : ?string,
     *   sort_name : string,
     *   sort_direction : 'asc' | 'desc',
     *   limit : int,
     * } $conditions
     * @return array{
     *     0: array<string>,
     *     1: array<array<string|int>>
     * }
     */
    public function getPdfData(array $conditions): array
    {
        $headers = $this->getHeader();
        $rows = $this->getDetail($conditions);

        return [$headers, $rows];
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
