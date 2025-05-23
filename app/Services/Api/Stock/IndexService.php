<?php

namespace App\Services\Api\Stock;

use App\Domain\Repositories\Stock\StockRepository;
use App\Dto\Response\Api\Stock\SearchResultDto;
use App\Dto\Response\Api\Stock\StockDto;
use App\Services\BaseService;

class IndexService extends BaseService
{
    private StockRepository $stockRepository;

    public function __construct(
        StockRepository $stockRepository
    ) {
        $this->stockRepository = $stockRepository;
    }

    /**
     * 商品を検索します。
     */
    public function searchStock(): SearchResultDto
    {
        $paginator = $this->stockRepository->getByLimit(6);

        $stocks = [];
        foreach ($paginator->getCollection() as $stock) {
            $stocks[] = new StockDto($stock);
        }

        return new SearchResultDto(
            $stocks,
            $paginator->currentPage(),
            $paginator->total()
        );
    }
}
