<?php

namespace App\Dto\Response\Api\Stock;

use App\Dto\Response\Api\BaseJsonDto;

class SearchResultDto extends BaseJsonDto
{
    /**
     * @param array<StockDto> $stocks
     */
    public function __construct(
        public array $stocks,
        public int $currentPage,
        public int $total,
    ) {
        parent::__construct(true);
    }
}
