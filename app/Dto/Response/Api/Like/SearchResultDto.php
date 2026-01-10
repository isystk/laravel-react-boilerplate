<?php

namespace App\Dto\Response\Api\Like;

use App\Dto\Response\Api\BaseJsonDto;

class SearchResultDto extends BaseJsonDto
{
    /**
     * @param array<int> $stockIds
     */
    public function __construct(
        public array $stockIds,
    ) {
        parent::__construct(true);
    }
}
