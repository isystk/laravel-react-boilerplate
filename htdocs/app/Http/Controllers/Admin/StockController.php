<?php

namespace App\Http\Controllers\Admin;

use App\Domain\Entities\Stock;
use App\Http\Controllers\BaseController;
use App\Http\Requests\StoreStockFormRequest;
use App\Services\Excel\ExcelStockService;
use App\Services\StockService;
use App\Utils\CSVUtil;
use Barryvdh\DomPDF\PDF;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class StockController extends BaseController
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
        $service = app(ExcelStockService::class);
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

        $service = app(PDF::class);
        return $service->loadView('admin.stock.pdf', compact('csvHeader', 'csvBody'))
            ->download('stocks.pdf');
    }

    /**
     * 商品登録画面の初期表示
     *
     * @param Request $request
     * @return View
     */
    public function create(Request $request): View
    {
        return view('admin.stock.create');
    }

    /**
     * 商品登録画面の登録処理
     *
     * @param StoreStockFormRequest $request
     * @return RedirectResponse
     * @throws \Exception
     */
    public function store(StoreStockFormRequest $request): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $service = app(StockService::class);
            $service->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        return redirect(route('admin.stock'));
    }


    /**
     * 商品詳細画面の登録処理
     *
     * @param Stock $stock
     * @return View
     */
    public function show(Stock $stock): View
    {
        return view('admin.stock.show', compact('stock'));
    }

    /**
     * 商品変更画面の初期表示
     *
     * @param Stock $stock
     * @return View
     */
    public function edit(Stock $stock): View
    {
        return view('admin.stock.edit', compact('stock'));
    }

    /**
     * 商品変更画面の登録処理
     *
     * @param StoreStockFormRequest $request
     * @param Stock $stock
     * @return RedirectResponse
     * @throws \Exception
     */
    public function update(StoreStockFormRequest $request, Stock $stock): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $service = app(StockService::class);
            $service->save($stock->id);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        return redirect(route('admin.stock'));
    }

    /**
     * 商品詳細画面の削除処理
     *
     * @param Stock $stock
     * @return RedirectResponse
     * @throws \Exception
     */
    public function destroy(Stock $stock): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $service = app(StockService::class);
            $service->delete($stock->id);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        return redirect(route('admin.stock'));
    }
}
