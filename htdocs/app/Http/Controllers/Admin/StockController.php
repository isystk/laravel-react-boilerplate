<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Stock;
use App\Utils\CSVUtil;
use App\Http\Requests\StoreStockFormRequest;
use App\Services\StockService;
use App\Services\Excel\ExcelStockService;

use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class StockController extends Controller
{
    /**
     * @var StockService
     * @var ExcelStockService
     * @var PDF
     */
    protected StockService $stockService;
    protected ExcelStockService $excelStockService;
    protected PDF $pdfService;

    public function __construct(StockService $stockService, ExcelStockService $excelStockService, PDF $pdfService)
    {
        $this->stockService = $stockService;
        $this->excelStockService = $excelStockService;
        $this->pdfService = $pdfService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {

        $name = $request->input('name');

        $stocks = $this->stockService->list();

        return view('admin.stock.index', compact('stocks', 'name'));
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return BinaryFileResponse|Response
     */
    public function downloadExcel(Request $request): Response|BinaryFileResponse
    {
        return $this->excelStockService->setTemplate(resource_path('excel/template.xlsx'))->download('stocks.xlsx');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     * @throws BindingResolutionException
     */
    public function downloadCsv(Request $request): Response
    {

        $stocks = $this->stockService->list(0);

        $csvHeader = ['ID', '商品名', '価格'];
        $csvBody = [];
        foreach ($stocks as $stock) {
            $line = [];
            $line[] = $stock->id;
            $line[] = $stock->name;
            $line[] = $stock->price;
            $csvBody[] = $line;
        }
        return CSVUtil::download($csvBody, $csvHeader, 'stocks.csv');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     */
    public function downloadPdf(Request $request): Response
    {

        $stocks = $this->stockService->list(0);

        $csvHeader = ['ID', '商品名', '価格'];
        $csvBody = [];
        foreach ($stocks as $stock) {
            $line = [];
            $line[] = $stock->id;
            $line[] = $stock->name;
            $line[] = $stock->price;
            $csvBody[] = $line;
        }

        $pdf = $this->pdfService->loadView('admin.stock.pdf', compact('csvHeader', 'csvBody'));
        return $pdf->download('stocks.pdf');
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function create(Request $request): View
    {
        return view('admin.stock.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreStockFormRequest $request
     * @return RedirectResponse
     */
    public function store(StoreStockFormRequest $request): RedirectResponse
    {

        $this->stockService->save();

        return redirect('admin/stock');
    }


    /**
     * Display the specified resource.
     *
     * @param Stock $stock
     * @return View
     */
    public function show(Stock $stock): View
    {
        return view('admin.stock.show', compact('stock'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Stock $stock
     * @return View
     */
    public function edit(Stock $stock): View
    {
        return view('admin.stock.edit', compact('stock'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreStockFormRequest $request
     * @param Stock $stock
     * @return RedirectResponse
     */
    public function update(StoreStockFormRequest $request, Stock $stock)
    {
        $this->stockService->save($stock->id);

        return redirect('admin/stock');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Stock $stock
     * @return RedirectResponse
     */
    public function destroy(Stock $stock): RedirectResponse
    {

        $this->stockService->delete($stock->id);

        return redirect('/admin/stock');
    }
}
