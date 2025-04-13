<?php

namespace App\FileIO\Exports;

use App\Domain\Entities\Stock;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StockExport implements FromCollection, WithHeadings
{
    /** @var Collection<int, Stock> $stocks */
    protected Collection $stocks;

    /**
     * コンストラクタ
     *
     * @param Collection<int, Stock> $stocks 商品のコレクション
     */
    public function __construct(Collection $stocks)
    {
        $this->stocks = $stocks;
    }

    /**
     * 商品のデータをコレクションとして返します。
     *
     * @return Collection<int, array{
     *     id: int,
     *     name: string,
     *     price: int
     * }> エクスポート用にフォーマットされた商品のコレクション
     */
    public function collection(): Collection
    {
        return $this->stocks->map(function (Stock $stock) {
            return [
                'id' => $stock->id,
                'name' => $stock->name,
                'price' => $stock->price,
            ];
        });
    }

    /**
     * エクスポートファイルのヘッダーを返します。
     *
     * @return string[] エクスポートファイルのヘッダー
     */
    public function headings(): array
    {
        return [
            'ID',
            '商品名',
            '価格'
        ];
    }

}
