<?php

namespace Feature\Http\Controllers\Api;

use App\Utils\ConstUtil;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConstControllerTest extends TestCase
{
    /**
     * 各テストの実行後にテーブルを空にする。
     */
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * 定数の一覧APIの取得テスト
     */
    public function testIndex(): void
    {
        $response = $this->get(route('api.consts'));
        $response->assertSuccessful();
        $consts = ConstUtil::searchConst();
        foreach ($consts as $const) {
            $response->assertSee($const['name']);
        }
    }

}
