<?php

namespace Tests\Unit\Services\Admin\Home;

use App\Services\Admin\Home\IndexService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;

class IndexServiceTest extends BaseTest
{
    use RefreshDatabase;

    private IndexService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(IndexService::class);
    }

    public function test_getTodaysOrdersCount_注文がない場合(): void
    {
        $count = $this->service->getTodaysOrdersCount();
        $this->assertSame(0, $count);
    }

    public function test_getTodaysOrdersCount_本日の注文のみカウントされる(): void
    {
        $user = $this->createDefaultUser();

        $this->createDefaultOrder(['user_id' => $user->id, 'created_at' => Carbon::today()]);
        $this->createDefaultOrder(['user_id' => $user->id, 'created_at' => Carbon::today()]);
        $this->createDefaultOrder(['user_id' => $user->id, 'created_at' => Carbon::yesterday()]);

        $count = $this->service->getTodaysOrdersCount();
        $this->assertSame(2, $count, '本日の注文のみがカウントされること');
    }

    public function test_getSalesByMonth_データがない場合(): void
    {
        $result = $this->service->getSalesByMonth();
        $this->assertSame(0, $result->count());
    }

    public function test_getSalesByMonth_年月昇順でフォーマットされたデータが返る(): void
    {
        $this->createDefaultMonthlySale(['year_month' => '202406', 'amount' => 30000]);
        $this->createDefaultMonthlySale(['year_month' => '202404', 'amount' => 10000]);
        $this->createDefaultMonthlySale(['year_month' => '202405', 'amount' => 0]);

        $result = $this->service->getSalesByMonth();

        $this->assertSame(3, $result->count());
        $this->assertSame('202404', $result[0]['year_month']);
        $this->assertSame(10000, $result[0]['amount']);
        $this->assertSame('202405', $result[1]['year_month']);
        $this->assertSame(0, $result[1]['amount'], '0の場合は0になること');
        $this->assertSame('202406', $result[2]['year_month']);
        $this->assertSame(30000, $result[2]['amount']);
    }

    public function test_getLatestOrders_注文がない場合(): void
    {
        $orders = $this->service->getLatestOrders();
        $this->assertSame(0, $orders->count());
    }

    public function test_getLatestOrders_最新順にデフォルト10件取得される(): void
    {
        $user = $this->createDefaultUser();

        for ($i = 1; $i <= 12; $i++) {
            $this->createDefaultOrder([
                'user_id'    => $user->id,
                'created_at' => sprintf('2024-%02d-01', $i),
            ]);
        }

        $orders = $this->service->getLatestOrders();
        $this->assertSame(10, $orders->count(), 'デフォルトで10件取得されること');
        $this->assertTrue(
            $orders->first()->created_at > $orders->last()->created_at,
            '最新順で取得されること'
        );
    }

    public function test_getLatestOrders_件数を指定できる(): void
    {
        $user = $this->createDefaultUser();

        for ($i = 1; $i <= 5; $i++) {
            $this->createDefaultOrder(['user_id' => $user->id]);
        }

        $orders = $this->service->getLatestOrders(3);
        $this->assertSame(3, $orders->count(), '指定した件数のみ取得されること');
    }

    // -------------------------
    // getUnrepliedContactsCount
    // -------------------------

    public function test_getUnrepliedContactsCount_お問い合わせがない場合(): void
    {
        $count = $this->service->getUnrepliedContactsCount();
        $this->assertSame(0, $count);
    }

    public function test_getUnrepliedContactsCount_返信あり返信なしが混在する場合_未返信のみカウントされる(): void
    {
        // 返信なし: 2件
        $this->createDefaultContact();
        $this->createDefaultContact();
        // 返信あり: 1件
        $repliedContact = $this->createDefaultContact();
        $this->createDefaultContactReply(['contact_id' => $repliedContact->id]);

        $count = $this->service->getUnrepliedContactsCount();
        $this->assertSame(2, $count, '未返信のお問い合わせのみカウントされること');
    }

    public function test_getUnrepliedContactsCount_すべて返信済みの場合_0を返す(): void
    {
        $contact1 = $this->createDefaultContact();
        $contact2 = $this->createDefaultContact();
        $this->createDefaultContactReply(['contact_id' => $contact1->id]);
        $this->createDefaultContactReply(['contact_id' => $contact2->id]);

        $count = $this->service->getUnrepliedContactsCount();
        $this->assertSame(0, $count, '全件返信済みの場合は0になること');
    }

    // -------------------------
    // getUsersByMonth
    // -------------------------

    public function test_getUsersByMonth_ユーザーがない場合(): void
    {
        $result = $this->service->getUsersByMonth();
        $this->assertSame(0, $result->count());
    }

    public function test_getUsersByMonth_月別にユーザー数が集計される(): void
    {
        $thisMonth = Carbon::now()->format('Y/m');
        $lastMonth = Carbon::now()->subMonth()->format('Y/m');

        // 今月: 2名
        $this->createDefaultUser(['created_at' => Carbon::now()->startOfMonth()->addDays(5)]);
        $this->createDefaultUser(['created_at' => Carbon::now()->startOfMonth()->addDays(10)]);
        // 先月: 1名
        $this->createDefaultUser(['created_at' => Carbon::now()->subMonth()->startOfMonth()->addDays(5)]);

        $result = $this->service->getUsersByMonth();

        $thisMonthData = $result->firstWhere('year_month', $thisMonth);
        $lastMonthData = $result->firstWhere('year_month', $lastMonth);

        $this->assertNotNull($thisMonthData, '今月のデータが含まれること');
        $this->assertSame(2, $thisMonthData['count'], '今月の新規ユーザーが2件カウントされること');
        $this->assertNotNull($lastMonthData, '先月のデータが含まれること');
        $this->assertSame(1, $lastMonthData['count'], '先月の新規ユーザーが1件カウントされること');
    }

    public function test_getUsersByMonth_昇順で返る(): void
    {
        $this->createDefaultUser(['created_at' => Carbon::now()->startOfMonth()]);
        $this->createDefaultUser(['created_at' => Carbon::now()->subMonth()->startOfMonth()]);
        $this->createDefaultUser(['created_at' => Carbon::now()->subMonths(2)->startOfMonth()]);

        $result = $this->service->getUsersByMonth();

        $this->assertSame(3, $result->count());
        $this->assertTrue(
            $result[0]['year_month'] < $result[1]['year_month'],
            '年月の昇順で返ること'
        );
        $this->assertTrue(
            $result[1]['year_month'] < $result[2]['year_month'],
            '年月の昇順で返ること'
        );
    }

    public function test_getUsersByMonth_範囲外のユーザーは含まれない(): void
    {
        // 今月（範囲内）
        $this->createDefaultUser(['created_at' => Carbon::now()->startOfMonth()->addDays(1)]);
        // 2ヶ月前（months=1 の場合は範囲外）
        $this->createDefaultUser(['created_at' => Carbon::now()->subMonths(2)->startOfMonth()]);

        $result = $this->service->getUsersByMonth(1);

        $this->assertSame(1, $result->count(), '今月分のみ1グループが返ること');
        $this->assertSame(1, $result->first()['count'], '今月の新規ユーザーが1件のみカウントされること');
    }

    public function test_getUsersByMonth_月数を指定できる(): void
    {
        // 今月（範囲内）
        $this->createDefaultUser(['created_at' => Carbon::now()->startOfMonth()]);
        // 先月（months=2 の場合は範囲内）
        $this->createDefaultUser(['created_at' => Carbon::now()->subMonth()->startOfMonth()]);
        // 3ヶ月前（months=2 の場合は範囲外）
        $this->createDefaultUser(['created_at' => Carbon::now()->subMonths(3)->startOfMonth()]);

        $result = $this->service->getUsersByMonth(2);
        $this->assertSame(2, $result->count(), '直近2ヶ月分のグループのみ返ること');
    }
}
