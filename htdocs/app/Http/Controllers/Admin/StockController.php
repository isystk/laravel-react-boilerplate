<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Stock;
use App\Services\Utils\CSVService;
use App\Http\Requests\StoreStockFormRequest;
use App\Services\StockService;
use App\Services\Excel\ExcelStockService;

use Barryvdh\DomPDF\PDF;

class StockController extends Controller
{
  /**
   * @var StockService
   * @var ExcelStockService
   * @var PDF
   */
  protected $stockService;
  protected $excelStockService;
  protected $pdfService;

  public function __construct(StockService $stockService, ExcelStockService $excelStockService, PDF $pdfService)
  {
      $this->stockService = $stockService;
      $this->excelStockService = $excelStockService;
      $this->pdfService = $pdfService;
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {

    $name = $request->input('name');

    $stocks = $this->stockService->list();

    return view('admin.stock.index', compact('stocks', 'name'));
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function downloadExcel(Request $request)
  {
    return $this->excelStockService->setTemplate(resource_path('excel/template.xlsx'))->download('stocks.xlsx');
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function downloadCsv(Request $request)
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
    return CSVService::download($csvBody, $csvHeader, 'stocks.csv');
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function downloadPdf(Request $request)
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
   * @return \Illuminate\Http\Response
   */
  public function create(Request $request)
  {
    //
    return view('admin.stock.create');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(StoreStockFormRequest $request)
  {

    $this->stockService->save();

    return redirect('admin/stock');
  }


  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $stock = $this->stockService->find($id);

    return view('admin.stock.show', compact('stock'));
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $stock = $this->stockService->find($id);

    return view('admin.stock.edit', compact('stock'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(StoreStockFormRequest $request, $id)
  {

    $this->stockService->save($id);

    return redirect('admin/stock');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {

    $this->stockService->delete($id);

    return redirect('/admin/stock');
  }
}
