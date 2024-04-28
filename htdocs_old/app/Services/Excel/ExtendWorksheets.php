<?php

namespace App\Services\Excel;

use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\RichText\RichText;

class ExtendWorksheets
{
    private ?Worksheet $worksheets = null;

    // コンストラクタを追加
    public function __construct(Worksheet $worksheets)
    {
        $this->worksheets = $worksheets;
    }

    /**
     * セルの値を取得
     * @param string $cell_label
     * @return mixed
     */
    public function getValue(string $cell_label): mixed
    {
        return $this->worksheets->getCell($cell_label)->getValue();
    }

    /**
     * セルへ値を上書き設定
     * (書式はテンプレートを継承する)
     * @param string $cell_label
     * @param string $value
     * @return $this
     * @throws Exception
     */
    public function setValue(string $cell_label, string $value): static
    {
        $this->worksheets->getCell($cell_label)->setValue($value);
        return $this;
    }

    /**
     * セルへ書式指定付きで値を上書き設定
     * @param string $cell_label
     * @param array<string, array<string>> $values_ary
     * @return $this
     * @throws Exception
     */
    public function setValueWithFormat(string $cell_label, array $values_ary): static
    {
        $RichText = new RichText();
        foreach ($values_ary as $key => $value) {
            $specialFont = $RichText->createTextRun($value['value'])->getFont();
            if (array_key_exists('size', $value)) {
                $specialFont->setSize($value['size']);
            }
            if (array_key_exists('style', $value)) {
                switch ($value['size']) {
                    case 'bold':
                        $specialFont->setBold(true);
                        break;
                    case 'italic':
                        $specialFont->setItalic(true);
                        break;
                }
            }
            if (array_key_exists('family', $value)) {
                $specialFont->setName($value['family']);
            }
        }
        $this->worksheets->getCell($cell_label)->setValue($RichText);
        return $this;
    }

    /**
     * セルへ値を指定キーワードを置換して設定
     * (書式はテンプレートを継承する)
     * @param string $cell_label
     * @param array<object> $values_ary
     * @return $this
     * @throws Exception
     */
    public function replaceValue(string $cell_label, array $values_ary): static
    {
        $setValue = self::getValue($cell_label);
        foreach ($values_ary as $key => $value) {
            $setValue = str_replace($value['target'], $value['value'], $setValue);
        }
        self::setValue($cell_label, $setValue);

        return $this;
    }

    /**
     * セル結合
     * @param string $range_label
     * @return $this
     * @throws Exception
     */
    public function mergeCells(string $range_label): static
    {
        $this->worksheets->mergeCells($range_label);
        return $this;
    }

    /**
     * セル結合解除
     * @param string $range_label
     * @return $this
     * @throws Exception
     */
    public function unmergeCells(string $range_label): static
    {
        $this->worksheets->unmergeCells($range_label);
        return $this;
    }

    /**
     * 複数セルへ値を範囲上書き設定
     * (書式はテンプレートを継承する)
     * @param array<int, array<int, mixed>> $source
     * @param mixed|null $nullValue
     * @param string $startCell
     * @param bool $strictNullComparison
     * @return $this
     */
    public function fromArray(array $source, mixed $nullValue = null, string $startCell = 'A1', bool $strictNullComparison = false): static
    {
        $this->worksheets->fromArray($source, $nullValue, $startCell, $strictNullComparison);
        return $this;
    }
}
