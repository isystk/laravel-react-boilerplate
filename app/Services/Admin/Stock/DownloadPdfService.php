<?php

namespace App\Services\Admin\Stock;

use App\Domain\Entities\Stock;
use App\Domain\Repositories\Stock\StockRepository;
use Illuminate\Http\Request;

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
     * @return array{
     *     0: array<string>,
     *     1: array<array<string|int>>
     * }
     */
    public function getPdfData(Request $request): array
    {
        $conditions = $this->convertConditionsFromRequest($request, 0);
        $stocks = $this->stockRepository->getByConditions($conditions);

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
