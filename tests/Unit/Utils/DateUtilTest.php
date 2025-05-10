<?php

namespace Tests\Unit\Utils;

use App\Utils\DateUtil;
use Carbon\CarbonImmutable;
use Tests\TestCase;

class DateUtilTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * 日付が空の場合、toCarbonImmutableがnullを返すことをテスト
     */
    public function test_to_carbon_immutable_returns_null_if_date_is_empty(): void
    {
        $this->assertNull(DateUtil::toCarbon(''));
    }

    /**
     * 日付がnullの場合、toCarbonImmutableがnullを返すことをテスト
     */
    public function test_to_carbon_immutable_returns_null_if_date_is_null(): void
    {
        $this->assertNull(DateUtil::toCarbon(null));
    }

    /**
     * 有効な日付文字列が提供された場合、toCarbonImmutableがCarbonImmutableオブジェクトを返すことをテスト
     *
     * @testWith ["2024-03-24 12:30:45"]
     */
    public function test_to_carbon_immutable_returns_carbon_immutable_if_valid_date_string_is_provided(string $date): void
    {
        $this->assertInstanceOf(CarbonImmutable::class, DateUtil::toCarbon($date));
    }

    /**
     * 年月のみが提供された場合、toCarbonImmutableが月初のCarbonImmutableオブジェクトを返すことをテスト
     *
     * @testWith ["2024-03"]
     */
    public function test_to_carbon_immutable_returns_carbon_immutable_with_default_day_if_only_year_and_month_are_provided(string $date
    ): void {
        $carbon = DateUtil::toCarbon($date);
        $this->assertEquals('2024-03-01 00:00:00', $carbon->format('Y-m-d H:i:s'));
    }

    /**
     * 時間が含まれない場合、toCarbonImmutableが00:00:00を持つCarbonImmutableオブジェクトを返すことをテスト
     *
     * @testWith ["2024-03-24"]
     */
    public function test_to_carbon_immutable_returns_carbon_immutable_with_default_time_if_no_time_is_provided(string $date): void
    {
        $carbon = DateUtil::toCarbon($date);
        $this->assertEquals('2024-03-24 00:00:00', $carbon->format('Y-m-d H:i:s'));
    }

    /**
     * 不正な日付文字列が提供された場合、toCarbonImmutableがnullを返すことをテスト
     */
    public function test_to_carbon_immutable_returns_null_if_invalid_date_string_is_provided(): void
    {
        $this->assertNull(DateUtil::toCarbon('invalid_date'));
    }

    /**
     * ハイフンが含まれる日付文字列が提供された場合、toCarbonImmutableがスラッシュでの日付変換を行うことをテスト
     *
     * @testWith ["2024-03-24 12:30:45", "2024/03/24 12:30:45"]
     */
    public function test_to_carbon_immutable_converts_hyphen_to_slash_in_date_string(string $dateHyphen, string $dateSlash): void
    {
        $carbonHyphen = DateUtil::toCarbon($dateHyphen);
        $carbonSlash = DateUtil::toCarbon($dateSlash);
        $this->assertEquals($carbonHyphen, $carbonSlash);
    }
}
