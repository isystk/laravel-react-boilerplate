<?php

namespace App\Dto\Response\Api;

class BaseJsonDto
{
    // 処理結果
    public bool $result;

    // メッセージ
    public string $message;

    public function __construct(
        bool $result,
    ) {
        $this->result = $result;
        // メッセージは必要に応じて後から設定する。
        $this->message = '';
    }
}
