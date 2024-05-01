<?php

namespace App\Http\Controllers\Admin\Stock;

use App\Domain\Entities\Stock;
use App\Http\Controllers\BaseController;
use App\Services\Admin\Stock\DownloadCsvService;
use App\Services\Admin\Stock\DownloadExcelService;
use App\Services\Admin\Stock\DownloadPdfService;
use App\Services\Admin\Stock\IndexService;
use App\Utils\CsvUtil;
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
        /** @var IndexService $service */
        $service = app(IndexService::class);
        $stocks = $service->searchStock();

        return view('admin.stock.index', compact('stocks', 'request'));
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
        /** @var DownloadCsvService $service */
        $service = app(DownloadCsvService::class);
        $csvData = $service->getCsvData();

        return response()->make($csvData, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=stocks.csv",
        ]);
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
     * 商品一覧画面のPDFダウンロード処理
     *
     * @param Request $request
     * @return Response
     */
    public function downloadPdf(Request $request): Response
    {
        /** @var DownloadPdfService $service */
        $service = app(DownloadPdfService::class);
        [$headers, $rows] = $service->getPdfData();

        /** @var PDF $pdf */
        $pdf = app(PDF::class);
        return $pdf->loadView('admin.stock.pdf', compact('headers', 'rows'))
            ->download('stocks.pdf');
    }
}
