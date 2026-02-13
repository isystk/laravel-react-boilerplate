<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use Illuminate\View\View;
use App\Domain\Entities\Order;
use App\Domain\Entities\MonthlySale;
use Illuminate\Support\Carbon;

class HomeController extends BaseController
{
    /**
     * ホーム画面の初期表示
     */
    public function index(): View
    {
        // 今日の注文数
        $todaysOrdersCount = Order::whereDate('created_at', Carbon::now()->toDateString())->count();

        // 月次売上（monthly_sales テーブルから取得）
        // year_month (例: "2026-02") と amount を取得して、Highcharts 用の配列に整形
        $monthlySales = MonthlySale::orderBy('year_month')->get(['year_month', 'amount']);
        $salesByMonth = $monthlySales->map(function ($m) {
            return [
                'year_month' => $m->year_month,
                'amount' => (int) ($m->amount ?? 0),
            ];
        })->values();

        // 最新の注文（最新10件）
        $latestOrders = Order::with('user')->latest('created_at')->take(10)->get();

        return view('admin.home',
            compact([
                'todaysOrdersCount',
                'salesByMonth',
                'latestOrders',
            ])
        );
    }
}
