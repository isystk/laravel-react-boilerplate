<?php

namespace App\Services\Admin\Stock;

use App\Domain\Entities\Stock;
use App\Domain\Repositories\Stock\StockRepository;
use App\Utils\CsvUtil;
use Illuminate\Http\Request;

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
     * @param Request $request
     * @return string
     */
    public function getCsvData(Request $request): string
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

        return CsvUtil::make($rows, $headers);
    }
}
