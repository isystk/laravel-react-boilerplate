<?php

namespace App\Services\Excel;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Files\LocalTemporaryFile;
use App\Services\StockService;
use Illuminate\Support\Facades\Request;

class ExcelStockService implements FromCollection, WithEvents
{
  use Exportable;

  /**
   * @var StockService
   */
  protected $stockService;

  public function __construct(StockService $stockService)
  {
      $this->stockService = $stockService;
  }

  private $template_file = null;

  /**
   * @param string $template_file
   * @return $this
   */
  public function setTemplate(string $template_file)
  {
      if (file_exists($template_file)) {
          $this->template_file = $template_file;
      }
      return $this;
  }

  /**
   * @return Collection
   */
  public function collection()
  {
      return collect([]);
  }

  /**
   * @return array
   */
  public function registerEvents(): array
  {
    return [
      // ファイル生成直前イベントハンドラ
      BeforeExport::class => function (BeforeExport $event) {
        // テンプレート読み込み
        if (is_null($this->template_file)) {
          return;
        }
        $event->writer->reopen(new LocalTemporaryFile($this->template_file), Excel::XLSX);
        $event->writer->getSheetByIndex(0);
        return $event->getWriter()->getSheetByIndex(0);
      },
      // 書き込み直前イベントハンドラ
      BeforeWriting::class => function (BeforeWriting $event) {
        // テンプレート読み込みでついてくる余計な空シートを削除する
        $event->writer->removeSheetByIndex(1);

        // セルを赤色で塗りつぶし
        // $event->writer->getSheetByIndex(0)->getDelegate()->getCell('B5')->getStyle('B5')->getFill()->setFillType('solid')->getStartColor()->setARGB('FFFFFF00');
        // $event->writer->getSheetByIndex(0)->getDelegate()->getCell('B6')->getStyle('B6')->getFill()->setFillType('solid')->getStartColor()->setARGB('FFFF0000');

        // // セル結合
        // $event->writer->getSheetByIndex(0)->getDelegate()->mergeCells('B10:C11');

        // // セル結合解除
        // $event->writer->getSheetByIndex(0)->getDelegate()->unmergeCells('B13:C13');

        // // 上罫線
        // $event->writer->getSheetByIndex(0)->getDelegate()->getCell('B16')->getStyle('B16')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        // // 下罫線
        // $event->writer->getSheetByIndex(0)->getDelegate()->getCell('B18')->getStyle('B18')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        // // 左罫線
        // $event->writer->getSheetByIndex(0)->getDelegate()->getCell('B20')->getStyle('B20')->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        // // 右罫線
        // $event->writer->getSheetByIndex(0)->getDelegate()->getCell('B22')->getStyle('B22')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        // // 外枠
        // $event->writer->getSheetByIndex(0)->getDelegate()->getCell('B24')->getStyle('B24')->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        // // 太線
        // $event->writer->getSheetByIndex(0)->getDelegate()->getCell('B26')->getStyle('B26')->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);

        // // 二重線
        // $event->writer->getSheetByIndex(0)->getDelegate()->getCell('B28')->getStyle('B28')->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE);

        // // 点線
        // $event->writer->getSheetByIndex(0)->getDelegate()->getCell('B30')->getStyle('B30')->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOTTED);

        // // 斜め右上がり罫線
        // $event->writer->getSheetByIndex(0)->getDelegate()->getCell('B32')->getStyle('B32')->getBorders()->setDiagonalDirection(1)->getDiagonal()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        // // 斜め右下がり罫線
        // $event->writer->getSheetByIndex(0)->getDelegate()->getCell('B34')->getStyle('B34')->getBorders()->setDiagonalDirection(2)->getDiagonal()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        // // 文字サイズ
        // $event->writer->getSheetByIndex(0)->getDelegate()->getCell('B5')->getStyle('B5')->getFont()->setSize(16);

        // // 太字
        // $event->writer->getSheetByIndex(0)->getDelegate()->getCell('B6')->getStyle('B6')->getFont()->setBold(true);

        // // 斜体
        // $event->writer->getSheetByIndex(0)->getDelegate()->getCell('B7')->getStyle('B7')->getFont()->setItalic(true);

        // // 下線
        // $event->writer->getSheetByIndex(0)->getDelegate()->getCell('B8')->getStyle('B8')->getFont()->setUnderline(true);

        // // 書体
        // $event->writer->getSheetByIndex(0)->getDelegate()->getCell('B9')->getStyle('B9')->getFont()->setName('HGSｺﾞｼｯｸE');

        // // 色
        // $event->writer->getSheetByIndex(0)->getDelegate()->getCell('B10')->getStyle('B10')->getFont()->getColor()->setARGB('FFFF0000');

        // // 一括設定
        // $event->writer->getSheetByIndex(0)->getDelegate()->getCell('B11')->getStyle('B11')->applyFromArray([
        //   'font' => [
        //     'size' => '16',
        //     'bold' => true,
        //     'italic' => true,
        //     'underline' => true,
        //     'name' => 'ヒラギノ丸ゴ Pro',
        //     'color' => ['argb' => 'FFFF0000']
        //   ]
        // ]);

        // // 右寄せ
        // $event->writer->getSheetByIndex(0)->getDelegate()->getCell('B12')->getStyle('B12')->getAlignment()->setHorizontal('right');

        // // 中央寄せ
        // $event->writer->getSheetByIndex(0)->getDelegate()->getCell('B13')->getStyle('B13')->getAlignment()->setHorizontal('center');

        // // ライブラリクラスでセルに値設定
        // $event->writer->getSheetByIndex(0)->getDelegate()->getCell('B6')->setValue('これはプログラムで設定した値です。');

        // 独自ワークシートクラス
        $esh = new ExtendWorksheets($event->writer->getSheetByIndex(0)->getDelegate());

        // // 独自ワークシートクラスでセルに値設定
        // $esh->setValue('B9', 'これはプログラムで設定した値です。');

        // // 独自ワークシートクラスでセルに値設定
        // $esh->replaceValue('B12', [['target' => '{置換対象1}', 'value' => '!!プログラムで置換1!!'], ['target' => '{置換対象2}', 'value' => '!!プログラムで置換2!!']]);

        $stocks = $this->stockService->list(0);
        $datas = [];
        foreach($stocks as $key => $stock){
          $cellnum = ($key+2);
          // 枠線
          $event->writer->getSheetByIndex(0)->getDelegate()->getStyle('A'. $cellnum.':C'. $cellnum)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

          array_push($datas, [$stock->id, $stock->name, $stock->price]);
        }

        //csv用データを設範囲設定
        $esh->fromArray($datas, null, 'A2');

        // A1を選択しておく
        $event->writer->getSheetByIndex(0)->getDelegate()->getStyle('A1');

        return;
      },
    ];
  }
}
