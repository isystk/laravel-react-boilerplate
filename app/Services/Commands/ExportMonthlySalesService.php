<?php

namespace App\Services\Commands;

use App\Domain\Repositories\MonthlySale\MonthlySaleRepositoryInterface;
use App\Services\BaseService;
use Illuminate\Support\Facades\Validator;

class ExportMonthlySalesService extends BaseService
{
    public function __construct(private readonly MonthlySaleRepositoryInterface $monthlySaleRepository) {}

    /**
     * 引数の入力チェックを行い、問題がある場合はエラーメッセージを返却する
     *
     * @param  array<string, mixed> $data
     * @return string[]             エラーメッセージの配列
     */
    public function validateArgs(array $data): array
    {
        $rules = [
            'output_path' => [
                'required',
                'string',
            ],
        ];
        $messages   = [];
        $attributes = [
            'output_path' => '出力ファイルパス',
        ];
        $validator = Validator::make($data, $rules, $messages, $attributes);

        return $validator->errors()->all();
    }

    /**
     * CSV出力データを取得します。
     *
     * @return array{
     *     0: string[],
     *     1: string[][]
     * }
     */
    public function getCsvData(): array
    {
        $header = $this->getHeader();
        $detail = $this->getDetail();

        return [$header, $detail];
    }

    /**
     * CSVファイルに出力するヘッダーを返却します。
     *
     * @return string[]
     */
    private function getHeader(): array
    {
        return [
            '年月',
            '注文件数',
            '売上金額',
        ];
    }

    /**
     * CSVファイルに出力する内容を返却します。
     *
     * @return string[][]
     */
    private function getDetail(): array
    {
        // 出力対象の月別売上データを取得します。
        $monthlySales = $this->monthlySaleRepository->getAllOrderByYearMonthDesc();

        $rows = [];
        foreach ($monthlySales as $monthlySale) {
            $row    = [];
            $row[]  = $monthlySale->year_month ?? ''; // 年月
            $row[]  = (string) $monthlySale->order_count; // 注文件数
            $row[]  = (string) $monthlySale->amount; // 売上金額
            $rows[] = $row;
        }

        return $rows;
    }
}
