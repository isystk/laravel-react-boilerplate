<?php

namespace App\Http\Controllers\Admin\Stock;

use App\Domain\Entities\Stock;
use App\Http\Controllers\BaseController;
use App\Services\Admin\Stock\DownloadExcelService;
use App\Services\Admin\Stock\StockService;
use App\Utils\CSVUtil;
use Barryvdh\DomPDF\PDF;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ListController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * 商品一覧画面の初期表示
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        /** @var StockService $service */
        $service = app(StockService::class);
        $stocks = $service->list();

        return view('admin.stock.index', compact('stocks', 'request'));
    }

    /**
     * 商品一覧画面のExcelダウンロード処理
     *
     * @param Request $request
     * @return BinaryFileResponse|Response
     */
    public function downloadExcel(Request $request): Response|BinaryFileResponse
    {
        /** @var DownloadExcelService $service */
        $service = app(DownloadExcelService::class);
        return $service->setTemplate(resource_path('excel/template.xlsx'))
            ->download('stocks.xlsx');
    }

    /**
     * 商品一覧画面のCSVダウンロード処理
     *
     * @param Request $request
     * @return Response
     * @throws BindingResolutionException
     */
    public function downloadCsv(Request $request): Response
    {
        /** @var StockService $service */
        $service = app(StockService::class);
        $stocks = $service->list(0);

        $csvHeader = ['ID', '商品名', '価格'];
        $csvBody = [];
        foreach ($stocks as $stock) {
            if (!$stock instanceof Stock) {
                throw new \RuntimeException('An unexpected error occurred.');
            }
            $line = [];
            $line[] = $stock->id;
            $line[] = $stock->name;
            $line[] = $stock->price;
            $csvBody[] = $line;
        }
        return CSVUtil::download($csvBody, $csvHeader, 'stocks.csv');
    }

    /**
     * 商品一覧画面のPDFダウンロード処理
     *
     * @param Request $request
     * @return Response
     */
    public function downloadPdf(Request $request): Response
    {
        /** @var StockService $service */
        $service = app(StockService::class);
        $stocks = $service->list(0);

        $csvHeader = ['ID', '商品名', '価格'];
        $csvBody = [];
        foreach ($stocks as $stock) {
            if (!$stock instanceof Stock) {
                throw new \RuntimeException('An unexpected error occurred.');
            }
            $line = [];
            $line[] = $stock->id;
            $line[] = $stock->name;
            $line[] = $stock->price;
            $csvBody[] = $line;
        }

        /** @var PDF $pdf */
        $pdf = app(PDF::class);
        return $pdf->loadView('admin.stock.pdf', compact('csvHeader', 'csvBody'))
            ->download('stocks.pdf');
    }
}
