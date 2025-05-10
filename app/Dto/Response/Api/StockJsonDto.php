<?php

namespace App\Dto\Response\Api;

use App\Domain\Entities\Stock;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class StockJsonDto extends BaseJsonDto
{
    /** @var Collection<int, Stock> 商品 */
    public Collection $stocks;

    // 現在ページ
    public int $currentPage;

    // 合計ページ
    public int $total;

    /**
     * @param  LengthAwarePaginator<int, Stock>  $stocks
     */
    public function __construct(
        LengthAwarePaginator $stocks,
    ) {
        parent::__construct(true);
        $this->stocks = $stocks->getCollection();
        $this->currentPage = $stocks->currentPage();
        $this->total = $stocks->total();
    }
}
