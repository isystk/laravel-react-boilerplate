<?php

namespace App\Http\Controllers\Admin\Stock;

use App\Dto\Request\Admin\Stock\SearchConditionDto;
use App\Http\Controllers\BaseController;
use App\Services\Admin\Stock\ExportService;
use App\Services\Admin\Stock\IndexService;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ListController extends BaseController
{

    /**
     * 商品一覧画面の初期表示
     */
    public function index(Request $request): View
    {
        /** @var IndexService $service */
        $service = app(IndexService::class);

        $conditions = new SearchConditionDto($request);
        $stocks = $service->searchStock($conditions);

        return view('admin.stock.index', compact('stocks', 'request'));
    }

    /**
     * 商品一覧画面のエクスポート処理
     */
    public function export(Request $request): Response|BinaryFileResponse
    {
        $fileType = $request->file_type;
        if (!in_array($fileType, ['csv', 'xlsx', 'pdf'])) {
            abort(400);
        }

        /** @var ExportService $service */
        $service = app(ExportService::class);
        $conditions = new SearchConditionDto($request);
        $export = $service->getExport($conditions);

        if ('csv' === $fileType) {
            return Excel::download($export, 'stocks.csv', \Maatwebsite\Excel\Excel::CSV);
        }
        if ('pdf' === $fileType) {
            $headers = $export->headings();
            $rows = $export->collection();
            /** @var PDF $pdf */
            $pdf = app(PDF::class);
            return $pdf->loadView('admin.stock.pdf', compact('headers', 'rows'))
                ->download('stocks.pdf');
        }
        return Excel::download($export, 'stocks.xlsx');
    }

}
