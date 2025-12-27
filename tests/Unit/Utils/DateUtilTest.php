<?php

namespace Tests\Unit\Utils;

use App\Utils\DateUtil;
use Carbon\CarbonImmutable;
use Tests\BaseTest;

class DateUtilTest extends BaseTest
{
    public function test_to_carbon_日付が空の場合to_carbon_immutableがnullを返すこと(): void
    {
        $this->assertNull(DateUtil::toCarbon(''));
    }

    public function test_to_carbon_日付がnullの場合to_carbon_immutableがnullを返すこと(): void
    {
        $this->assertNull(DateUtil::toCarbon(null));
    }

    /**
     * @testWith ["2024-03-24 12:30:45"]
     */
    public function test_to_carbon_有効な日付文字列が提供された場合to_carbon_immutableが_carbon_immutableオブジェクトを返すこと(string $date): void
    {
        $this->assertInstanceOf(CarbonImmutable::class, DateUtil::toCarbon($date));
    }

    /**
     * @testWith ["2024-03"]
     */
    public function test_to_carbon_年月のみが提供された場合to_carbon_immutableが月初の_carbon_immutableオブジェクトを返すこと(string $date): void
    {
        $carbon = DateUtil::toCarbon($date);
        $this->assertEquals('2024-03-01 00:00:00', $carbon->format('Y-m-d H:i:s'));
    }

    /**
     * @testWith ["2024-03-24"]
     */
    public function test_to_carbon_時間が含まれない場合_carbon_immutableオブジェクトを返すこと(string $date): void
    {
        $carbon = DateUtil::toCarbon($date);
        $this->assertEquals('2024-03-24 00:00:00', $carbon->format('Y-m-d H:i:s'));
    }

    public function test_to_carbon_不正な日付文字列が提供された場合to_carbon_immutableがnullを返すこと(): void
    {
        $this->assertNull(DateUtil::toCarbon('invalid_date'));
    }

    /**
     * @testWith ["2024-03-24 12:30:45", "2024/03/24 12:30:45"]
     */
    public function test_to_carbon_ハイフンが含まれる日付文字列が提供された場合to_carbon_immutableがスラッシュでの日付変換を行うこと(string $dateHyphen, string $dateSlash): void
    {
        $carbonHyphen = DateUtil::toCarbon($dateHyphen);
        $carbonSlash = DateUtil::toCarbon($dateSlash);
        $this->assertEquals($carbonHyphen, $carbonSlash);
    }

    /**
     * @testWith ["2024/03"]
     */
    public function test_to_carbon_スラッシュ区切りの年月のみが提供された場合も月初の_carbon_immutableオブジェクトを返すこと(string $date): void
    {
        $carbon = DateUtil::toCarbon($date);
        $this->assertEquals('2024-03-01 00:00:00', $carbon->format('Y-m-d H:i:s'));
    }
}
