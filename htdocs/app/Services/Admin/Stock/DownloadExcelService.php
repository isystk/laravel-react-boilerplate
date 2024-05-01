<?php

namespace App\Services\Admin\Stock;

use App\Domain\Entities\Stock;
use App\Utils\ExtendWorksheets;
use Closure;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Files\LocalTemporaryFile;

class DownloadExcelService extends BaseStockService implements FromCollection, WithEvents
{
    use Exportable;

    private ?string $template_file = null;

    /**
     * @param string $template_file
     * @return $this
     */
    public function setTemplate(string $template_file): static
    {
        if (file_exists($template_file)) {
            $this->template_file = $template_file;
        }
        return $this;
    }

    /**
     * @return Collection
     */
    public function collection(): Collection
    {
        return collect([]);
    }

    /**
     * @return array<string, (Closure)|(Closure)>
     */
    public function registerEvents(): array
    {
        return [
            // ファイル生成直前イベントハンドラ
            BeforeExport::class => function (BeforeExport $event)
            {
                // テンプレート読み込み
                if (is_null($this->template_file)) {
                    return;
                }
                $event->writer->reopen(new LocalTemporaryFile($this->template_file), Excel::XLSX);
                $event->writer->getSheetByIndex(0);
                return $event->getWriter()->getSheetByIndex(0);
            },
            // 書き込み直前イベントハンドラ
            BeforeWriting::class => function (BeforeWriting $event)
            {
                // テンプレート読み込みでついてくる余計な空シートを削除する
                $event->writer->removeSheetByIndex(1);

                // 独自ワークシートクラス
                $esh = new ExtendWorksheets($event->writer->getSheetByIndex(0)->getDelegate());

                $stocks = $this->searchStock(0);
                $datas = [];
                foreach ($stocks as $key => $stock) {
                    if (!$stock instanceof Stock) {
                        throw new \RuntimeException('An unexpected error occurred.');
                    }
                    $cellnum = ($key + 2);
                    // 枠線
                    $event->writer->getSheetByIndex(0)->getDelegate()->getStyle('A' . $cellnum . ':C' . $cellnum)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                    $datas[] = [$stock->id, $stock->name, $stock->price];
                }

                //csv用データを設範囲設定
                $esh->fromArray($datas, null, 'A2');

                // A1を選択しておく
                $event->writer->getSheetByIndex(0)->getDelegate()->getStyle('A1');
            },
        ];
    }
}
