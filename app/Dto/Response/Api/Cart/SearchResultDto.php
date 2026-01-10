<?php

namespace App\Dto\Response\Api\Cart;

use App\Dto\Response\Api\BaseJsonDto;

class SearchResultDto extends BaseJsonDto
{
    /**
     * @param array<CartStockDto> $stocks
     */
    public function __construct(
        public array $stocks,
        public string $email,
        public int $sum,
        public int $count,
    ) {
        parent::__construct(true);
    }
}
