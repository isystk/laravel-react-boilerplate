<?php

namespace App\Services\Admin\Stock;

use App\Domain\Entities\Stock;
use App\Domain\Repositories\Stock\StockRepository;
use App\Utils\ExtendWorksheets;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Files\LocalTemporaryFile;
use RuntimeException;

class DownloadExcelService extends BaseStockService implements FromCollection, WithEvents
{
    use Exportable;

    private StockRepository $stockRepository;

    private string $templateFile;
    private Request $request;

    /**
     * Create a new controller instance.
     *
     * @param StockRepository $stockRepository
     */
    public function __construct(
        StockRepository $stockRepository
    )
    {
        $this->stockRepository = $stockRepository;
    }

    /**
     * Setup
     * @param string $templateFile
     * @param Request $request
     * @return $this
     */
    public function setUp(string $templateFile, Request $request): static
    {
        if (!file_exists($templateFile)) {
            throw new RuntimeException('The template file does not exist.');
        }
        $this->templateFile = $templateFile;
        $this->request = $request;
        return $this;
    }

    /**
     * @return Collection<int, mixed>
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
                $event->writer->reopen(new LocalTemporaryFile($this->templateFile), Excel::XLSX);
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

                $conditions = $this->convertConditionsFromRequest($this->request, 0);
                $stocks = $this->stockRepository->getByConditions($conditions);

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
