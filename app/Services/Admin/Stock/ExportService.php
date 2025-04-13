<?php

namespace App\Services\Admin\Stock;

use App\Domain\Repositories\Stock\StockRepository;
use App\FileIO\Exports\StockExport;

class ExportService extends BaseStockService
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
     * エクスポート用のオブジェクトを取得します。
     * @param array{
     *   name : ?string,
     *   sort_name : string,
     *   sort_direction : 'asc' | 'desc',
     *   limit : int,
     * } $conditions
     * @return StockExport
     */
    public function getExport(array $conditions): StockExport
    {
        $conditions['limit'] = null;
        $stocks = $this->stockRepository->getByConditions($conditions);
        return new StockExport($stocks);
    }

}
