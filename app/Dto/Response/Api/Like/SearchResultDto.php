<?php

namespace App\Dto\Response\Api\Like;

use App\Dto\Response\Api\BaseJsonDto;
use App\Dto\Response\Api\Stock\StockDto;

class SearchResultDto extends BaseJsonDto
{
    /**
     * @param array<int>      $stockIds
     * @param array<StockDto> $stocks
     */
    public function __construct(
        public array $stockIds,
        public array $stocks = [],
    ) {
        parent::__construct(true);
    }
}
