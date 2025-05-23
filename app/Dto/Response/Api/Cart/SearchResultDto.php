<?php

namespace App\Dto\Response\Api\Cart;

use App\Dto\Response\Api\BaseJsonDto;

class SearchResultDto extends BaseJsonDto
{
    /** @var array<CartStockDto> 商品 */
    public array $stocks;

    // メールアドレス
    public string $email;

    // 合計金額
    public int $sum;

    // 合計商品数
    public int $count;

    /**
     * @param  array<CartStockDto>  $stocks
     */
    public function __construct(
        array $stocks,
        string $email,
        int $sum,
        int $count,
    ) {
        parent::__construct(true);
        $this->stocks = $stocks;
        $this->email = $email;
        $this->sum = $sum;
        $this->count = $count;
    }
}
