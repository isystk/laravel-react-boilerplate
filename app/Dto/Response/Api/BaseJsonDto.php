<?php

namespace App\Dto\Response\Api;

class BaseJsonDto
{
    // 処理結果
    public bool $result;

    public function __construct(
        bool $result,
    ) {
        $this->result = $result;
    }
}
