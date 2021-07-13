<?php

namespace App\Services\Excel;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\RichText\RichText as RichText;

class ExtendWorksheets
{
  private $worksheets = null;

  // コンストラクタを追加
  public function __construct(Worksheet $worksheets)
  {
    $this->worksheets = $worksheets;
    return $this;
  }

  /**
   * セルの値を取得
   *
   * @return cell value
   */
  public function getValue($cell_label)
  {
    return $this->worksheets->getCell($cell_label)->getValue();
  }

  /**
   * セルへ値を上書き設定
   * (書式はテンプレートを継承する)
   *
   * @return $this
   */
  public function setValue($cell_label, $value)
  {
    $this->worksheets->getCell($cell_label)->setValue($value);
    return $this;
  }

  /**
   * セルへ書式指定付きで値を上書き設定
   *
   * @return $this
   */
  public function setValueWithFormat($cell_label, $values_ary)
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
   *
   * @return $this
   */
  public function replaceValue($cell_label, $values_ary)
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
   * @return $this
   */
  public function mergeCells($range_label)
  {
    $this->worksheets->mergeCells($range_label);
    return $this;
  }

  /**
   * セル結合解除
   * @return $this
   */
  public function unmergeCells($range_label)
  {
    $this->worksheets->unmergeCells($range_label);
    return $this;
  }

  /**
   * 複数セルへ値を範囲上書き設定
   * (書式はテンプレートを継承する)
   *
   * @return $this
   */
  public function fromArray(array $source, $nullValue = null, $startCell = 'A1', $strictNullComparison = false)
  {
    $this->worksheets->fromArray($source, $nullValue, $startCell, $strictNullComparison);
    return $this;
  }
}
