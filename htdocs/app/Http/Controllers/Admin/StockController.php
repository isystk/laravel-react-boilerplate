<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Stock;
use App\Services\CSV;
use App\Services\UploadImage;
use App\Http\Requests\StoreStockForm;
use PDF;

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

        // 検索フォーム
        $query = DB::table('stocks');

        // もしキーワードがあったら
        if ($name !== null) {
            $query->where('name', 'like', '%' . $name . '%');
        }

        $query->select('id', 'name', 'detail', 'price', 'quantity', 'created_at');
        $query->orderBy('id');
        $query->orderBy('created_at', 'desc');
        $stocks = $query->paginate(20);

        // dd($stocks);

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

        // 検索フォーム
        $query = DB::table('stocks');

        // もしキーワードがあったら
        if ($name !== null) {
            $query->where('name', 'like', '%' . $name . '%');
        }

        $query->select('id', 'name', 'price');
        $query->orderBy('id');
        $stocks = $query->get();

        // dd($stocks);

        $csvHeader = ['ID', '商品名', '価格'];
        $csvBody = [];
        foreach ($stocks as $stock) {
            $line = [];
            $line[] = $stock->id;
            $line[] = $stock->name;
            $line[] = $stock->price;
            $csvBody[] = $line;
        }
        return CSV::download($csvBody, $csvHeader, 'stocks.csv');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function downloadPdf(Request $request)
    {

        $name = $request->input('name');

        // 検索フォーム
        $query = DB::table('stocks');

        // もしキーワードがあったら
        if ($name !== null) {
            $query->where('name', 'like', '%' . $name . '%');
        }

        $query->select('id', 'name', 'price');
        $query->orderBy('id');
        $stocks = $query->get();

        // dd($stocks);

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
        // 画像ファイルを公開ディレクトリへ配置する。
        if ($request->has('imageBase64')) {
            $tmpFile = $request->validated()['imageFile'];

            $fileName = $request->fileName;

            //s3に画像をアップロード
            $tmpFile->storeAs('stock', $fileName);

            // //ストレージにも画像を保存
            // $target_path = storage_path('uploads/stock/');
            // $tmpFile->move($target_path, $fileName);
        } else {
            $fileName = "";
        }

        // お問い合わせテーブルを登録
        $stock = new Stock;
        $stock->name = $request->input('name');
        $stock->detail = $request->input('detail');
        $stock->price = $request->input('price');
        $stock->quantity = $request->input('quantity');

        if ($fileName !== "") {
            $stock->imgpath = $fileName;
        }

        $stock->save();

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
        // imageBase64パラメータがあればUploadedFileオブジェクトに変換してimageFileパラメータに上書きする。
        if ($request->has('imageBase64')) {
            $request['imageFile'] = UploadImage::converBase64($request->imageBase64);
        }

        // 入力チェック
        $validatedData = $request->validate([
            'name' => 'required|string|max:20',
            'price' => 'required|numeric',
            'detail' => 'required|string|max:200',
            'quantity' => 'required|numeric',
            'imageFile' => 'nullable|image|mimes:jpeg,png|max:100000000|dimensions:max_width=1200,max_height=1200', // ファイルのバリデーションよしなに。
            'imageBase64' => 'nullable|string', // 画像データをbase64で文字列としても受け入れる。バリデーションルールはimageFileが適用される。
            'fileName' => 'nullable|string', // 画像ファイル名
        ]);

        // 画像ファイルを公開ディレクトリへ配置する。
        if ($file = $request->imageFile) {
            $fileName = $request->fileName;

            //s3に画像をアップロード
            $file->storeAs('stock', $fileName);

            // // //ストレージにも画像を保存
            // $target_path = public_path('uploads/stock/');
            // $file->move($target_path, $fileName);
        } else {
            $fileName = "";
        }

        //
        $stock = Stock::find($id);

        $stock->name = $request->input('name');
        $stock->detail = $request->input('detail');
        $stock->price = $request->input('price');
        $stock->quantity = $request->input('quantity');

        if ($fileName !== "") {
            $stock->imgpath = $fileName;
        }

        $stock->save();

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
        // ユーザーテーブルを削除
        $stock = Stock::find($id);
        $stock->delete();

        return redirect('/admin/stock');
    }
}
