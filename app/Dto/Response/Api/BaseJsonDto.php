<?php

namespace App\Dto\Response\Api;

class BaseJsonDto
{
    // メッセージ
    public string $message;

    public function __construct(
        public bool $result,
    ) {
        // メッセージは必要に応じて後から設定する。
        $this->message = '';
    }
}
