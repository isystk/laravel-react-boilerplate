<?php

namespace App\Http\Controllers\Admin\Stock;

use App\Http\Controllers\BaseController;
use App\Services\Admin\Stock\DownloadCsvService;
use App\Services\Admin\Stock\DownloadExcelService;
use App\Services\Admin\Stock\DownloadPdfService;
use App\Services\Admin\Stock\IndexService;
use Barryvdh\DomPDF\PDF;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ListController extends BaseController
{

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

        $conditions = $service->convertConditionsFromRequest($request);
        $stocks = $service->searchStock($conditions);

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

        $conditions = $service->convertConditionsFromRequest($request, 0);
        $csvData = $service->getCsvData($conditions);

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
        return $service->setUp(storage_path('app/stock/excel/template.xlsx'), $request)
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

        $conditions = $service->convertConditionsFromRequest($request, 0);
        [$headers, $rows] = $service->getPdfData($conditions);

        /** @var PDF $pdf */
        $pdf = app(PDF::class);
        return $pdf->loadView('admin.stock.pdf', compact('headers', 'rows'))
            ->download('stocks.pdf');
    }
}
