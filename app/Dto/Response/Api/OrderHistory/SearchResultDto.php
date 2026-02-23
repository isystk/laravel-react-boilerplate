<?php

namespace App\Dto\Response\Api\OrderHistory;

use App\Dto\Response\Api\BaseJsonDto;

class SearchResultDto extends BaseJsonDto
{
    /**
     * @param array<OrderDto> $orders
     */
    public function __construct(
        public array $orders,
    ) {
        parent::__construct(true);
    }
}
