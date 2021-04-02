<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Services\CSV;
use PDF;

class OrderController extends Controller
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
        $query = DB::table('orders')
            ->join('users', 'users.id', '=', 'orders.user_id')
            ->join('stocks', 'stocks.id', '=', 'orders.stock_id');

        // もしキーワードがあったら
        if ($name !== null) {
            $query->where('users.name', 'like', '%' . $name . '%');
        }

        $query->select('orders.id', 'users.name as user_name', 'stocks.name as stock_name', 'orders.quantity', 'orders.created_at');
        $query->orderBy('orders.created_at', 'desc');
        $query->orderBy('orders.id', 'desc');
        $orders = $query->paginate(20);

        return view('admin.order.index', compact('orders', 'name'));
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
        $query = DB::table('orders')
            ->join('users', 'users.id', '=', 'orders.user_id')
            ->join('stocks', 'stocks.id', '=', 'orders.stock_id');

        // もしキーワードがあったら
        if ($name !== null) {
            $query->where('users.name', 'like', '%' . $name . '%');
        }

        $query->select('orders.id', 'users.name as user_name', 'stocks.name as stock_name', 'orders.quantity', 'orders.created_at');
        $query->orderBy('orders.created_at', 'desc');
        $query->orderBy('orders.id', 'desc');
        $orders = $query->get();

        // dd($orders);

        $csvHeader = ['ID', '注文者', '商品名', '個数', '発注日時'];
        $csvBody = [];
        foreach ($orders as $order) {
            $line = [];
            $line[] = $order->id;
            $line[] = $order->user_name;
            $line[] = $order->stock_name;
            $line[] = $order->quantity;
            $line[] = $order->created_at;
            $csvBody[] = $line;
        }
        return CSV::download($csvBody, $csvHeader, 'orders.csv');
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
        $query = DB::table('orders')
            ->join('users', 'users.id', '=', 'orders.user_id')
            ->join('stocks', 'stocks.id', '=', 'orders.stock_id');

        // もしキーワードがあったら
        if ($name !== null) {
            $query->where('users.name', 'like', '%' . $name . '%');
        }

        $query->select('orders.id', 'users.name as user_name', 'stocks.name as stock_name', 'orders.quantity', 'orders.created_at');
        $query->orderBy('orders.created_at', 'desc');
        $query->orderBy('orders.id', 'desc');
        $orders = $query->get();

        // dd($orders);

        $csvHeader = ['ID', '注文者', '商品名', '個数', '発注日時'];
        $csvBody = [];
        foreach ($orders as $order) {
            $line = [];
            $line[] = $order->id;
            $line[] = $order->user_name;
            $line[] = $order->stock_name;
            $line[] = $order->quantity;
            $line[] = $order->created_at;
            $csvBody[] = $line;
        }

        $pdf = PDF::loadView('admin.order.pdf', compact('csvHeader', 'csvBody'));
        return $pdf->download('orders.pdf');
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
        $order = Order::find($id);

        return view('admin.order.show', compact('order'));
    }
}
