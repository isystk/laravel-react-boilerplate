<?php

namespace Tests\Unit\Observers;

use App\Domain\Entities\MonthlySale;
use App\Observers\OrderObserver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\BaseTest;

class OrderObserverTest extends BaseTest
{
    use RefreshDatabase;

    public function test_created_MonthlySaleが存在しない場合は新規作成されること(): void
    {
        $createdAt = Carbon::parse('2025-01-15 10:00:00');

        // Orderを作成（Observerが発火）
        $this->createDefaultOrder([
            'sum_price'  => 1000,
            'created_at' => $createdAt,
        ]);

        $this->assertDatabaseHas('monthly_sales', [
            'year_month'  => '202501',
            'order_count' => 1,
            'amount'      => 1000,
        ]);
    }

    public function test_created_MonthlySaleが存在する場合は既存レコードが更新されること(): void
    {
        $yearMonth = '202501';
        $this->createDefaultMonthlySale([
            'year_month'  => $yearMonth,
            'order_count' => 5,
            'amount'      => 5000,
        ]);

        $this->createDefaultOrder([
            'sum_price'  => 2000,
            'created_at' => Carbon::parse('2025-01-20'),
        ]);

        $this->assertDatabaseHas('monthly_sales', [
            'year_month'  => $yearMonth,
            'order_count' => 6,
            'amount'      => 7000,
        ]);
    }

    public function test_updated_金額が変更された場合にMonthlySaleが更新されること(): void
    {
        $order = $this->createDefaultOrder([
            'sum_price'  => 1000,
            'created_at' => Carbon::parse('2025-02-01'),
        ]);

        // 金額を変更して保存（Observerのupdatedが発火）
        $order->sum_price = 3000;
        $order->save();

        // 既存の1000に加えて、新たに3000が加算されるロジック（※仕様通りの検証）
        $this->assertDatabaseHas('monthly_sales', [
            'year_month'  => '202502',
            'order_count' => 2,
            'amount'      => 4000,
        ]);
    }

    public function test_updated_金額以外が変更された場合はMonthlySaleは更新されないこと(): void
    {
        $order = $this->createDefaultOrder([
            'sum_price'  => 1000,
            'created_at' => Carbon::parse('2025-02-01'),
        ]);

        // MonthlySaleの状態を記録
        $beforeSale = MonthlySale::where('year_month', '202502')->first();

        // 金額以外を変更（例: statusなど。ここでは仮に他のカラムを想定）
        $order->updated_at = Carbon::now()->addMinute();
        $order->save();

        $afterSale = MonthlySale::where('year_month', '202502')->first();

        $this->assertEquals($beforeSale->amount, $afterSale->amount);
        $this->assertEquals($beforeSale->order_count, $afterSale->order_count);
    }

    public function test_empty_methods(): void
    {
        $order = $this->createDefaultOrder();

        $observer = new OrderObserver;
        $observer->deleted($order);
        $observer->restored($order);
        $observer->forceDeleted($order);

        /** @phpstan-ignore-next-line */
        $this->assertTrue(true);
    }
}
