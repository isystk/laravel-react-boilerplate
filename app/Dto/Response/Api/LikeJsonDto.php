<?php

namespace App\Dto\Response\Api;

class LikeJsonDto extends BaseJsonDto
{
    /** @var array<int> 商品ID */
    public array $stockIds;

    /**
     * @param array<int> $stockIds
     */
    public function __construct(
        array $stockIds,
    ) {
        parent::__construct(true);
        $this->stockIds = $stockIds;
    }

}
