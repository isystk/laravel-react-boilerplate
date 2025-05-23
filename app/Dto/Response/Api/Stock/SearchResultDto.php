<?php

namespace App\Dto\Response\Api\Stock;

use App\Dto\Response\Api\BaseJsonDto;

class SearchResultDto extends BaseJsonDto
{
    /** @var array<StockDto> 商品 */
    public array $stocks;

    // 現在ページ番号
    public int $currentPage;

    // 合計ページ数
    public int $total;

    /**
     * @param  array<StockDto>  $stocks
     */
    public function __construct(
        array $stocks,
        int $currentPage,
        int $total,
    ) {
        parent::__construct(true);
        $this->stocks = $stocks;
        $this->currentPage = $currentPage;
        $this->total = $total;
    }
}
