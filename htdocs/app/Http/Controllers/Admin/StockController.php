<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Stock;
use App\Services\CSVService;
use App\Http\Requests\StoreStockForm;

use PDF;
use StockService;

class StockController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {

    $name = $request->input('name');

    $stocks = StockService::searchStock($name, true);

    return view('admin.stock.index', compact('stocks', 'name'));
  }


  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function downloadCsv(Request $request)
  {

    $name = $request->input('name');

    $stocks = StockService::searchStock($name, false);

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

    $name = $request->input('name');

    $stocks = StockService::searchStock($name, false);

    $csvHeader = ['ID', '商品名', '価格'];
    $csvBody = [];
    foreach ($stocks as $stock) {
      $line = [];
      $line[] = $stock->id;
      $line[] = $stock->name;
      $line[] = $stock->price;
      $csvBody[] = $line;
    }

    $pdf = PDF::loadView('admin.stock.pdf', compact('csvHeader', 'csvBody'));
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
  public function store(StoreStockForm $request)
  {

    StockService::createStock($request);

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
    //
    $stock = Stock::find($id);

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
    //
    $stock = Stock::find($id);

    return view('admin.stock.edit', compact('stock'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {

    StockService::updateStock($request, $id);

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

    StockService::deleteStock($id);

    return redirect('/admin/stock');
  }
}
