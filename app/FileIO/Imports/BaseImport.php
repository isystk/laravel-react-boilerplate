<?php

namespace App\FileIO\Imports;

use App\Utils\DateUtil;
use Closure;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Excel;
use PhpOffice\PhpSpreadsheet\Shared\Date;

abstract class BaseImport implements WithMapping, WithStartRow, WithValidation
{
    use Importable;

    private int $startRow = 2;
    protected string $filePath;
    protected string $readerType;
    /**
     * @var array<int, string>
     */
    protected array $header = [];
    /**
     * @var array<string, string>
     */
    protected array $attribute = [];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
        $this->readerType = match (mime_content_type($filePath)) {
            'text/plain','text/csv' => Excel::CSV,
            'application/vnd.ms-excel' => Excel::XLS,
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => Excel::XLSX,
            default => throw new \RuntimeException('$extension is an unknown value'),
        };
        // ヘッダーを読み込んでおく
        $lines = \Maatwebsite\Excel\Facades\Excel::toArray(new Collection(), $this->filePath, null, $this->readerType)[0];
        $this->header = array_diff(array_shift($lines), [null]);
    }

    /**
     * @return array<int, array<int, array<string, string>>>
     */
    public function getSheets(): array
    {
        return $this->toArray($this->filePath);
    }

    /**
     * @return int
     */
    public function startRow(): int
    {
        return $this->startRow;
    }

    /**
     * @return array<int, string>
     */
    public function validate(): array
    {
        $errors = [];

        $rows = $this->getSheets()[0];
        foreach ($rows as $i => $row) {
            $validator = Validator::make($row, $this->rules(), $this->customValidationMessages(), $this->customValidationAttributes());
            if ($validator->fails()) {
                $messages = $validator->errors()->all();
                foreach ($messages as $message) {
                    $errors[] = $message . '（'.($i+$this->startRow).'行目）';
                }
            }
        }

        return $errors;
    }

    /**
     * エラーメッセージに表示する属性の名称を指定します
     * @return array<string, string>
     */
    protected function customValidationAttributes(): array
    {
        return array_flip($this->attribute);
    }

    /**
     * 配列データをヘッダーをキーとした連想配列に変換します
     * @param array<int, ?string> $row
     * @return array<string, ?string>
     */
    protected function getRowMap(array $row): array {
        return collect($this->header)->mapWithKeys(function ($col, $i) use($row) {
            $key = $this->attribute[$this->trimData($col)]??null;
            if (!is_string($key)) {
                return [(string)$key => $row[$i]];
            }
            return [$key => $row[$i]];
        })->all();
    }

    /**
     * Excelの場合に、書式が「日付」だと値が正しく取得できないので文字列に変換します
     * @param ?string $col
     * @param string $format
     * @return ?string
     */
    protected function convertDate(?string $col, string $format): ?string
    {
        $col = $this->trimData($col);
        if (!isset($col)) {
            return null;
        }

        $date = DateUtil::toCarbonImmutable($col);
        if (null !== $date) {
            return $date->format($format);
        }
        if (Excel::XLS === $this->readerType || Excel::XLSX === $this->readerType) {
            // Excelの書式が「日付」の場合は、数値が渡されるため、日付文字列に変換する
            if (is_numeric($col)) {
                // @phpstan-ignore-next-line
                return Date::excelToDateTimeObject($col)->format($format);
            }
        }
        return $col;
    }

    /**
     * 前後に空白がある場合は取り除きます
     * @param ?string $col
     * @return ?string
     */
    protected function trimData(?string $col): ?string
    {
        if (!isset($col)) {
            return null;
        }
        return trim($col);
    }

    /**
     * @param array<int, string> $row
     * @return array<string, string>
     */
    abstract public function map($row): array;

    /**
     * @return array<string, array<int, string|Enum|Closure>>
     */
    abstract public function rules(): array;

    /**
     * @return array<string, string>
     */
    abstract protected function customValidationMessages(): array;

}
