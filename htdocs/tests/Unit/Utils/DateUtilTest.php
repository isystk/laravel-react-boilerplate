<?php

namespace Tests\Unit\Utils;

use App\Utils\DateUtil;
use Carbon\CarbonImmutable;
use Tests\TestCase;

class DateUtilTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * 日付が空の場合、toCarbonImmutableがnullを返すことをテスト
     */
    public function testToCarbonImmutableReturnsNullIfDateIsEmpty(): void
    {
        $this->assertNull(DateUtil::toCarbonImmutable(''));
    }

    /**
     * 日付がnullの場合、toCarbonImmutableがnullを返すことをテスト
     */
    public function testToCarbonImmutableReturnsNullIfDateIsNull(): void
    {
        $this->assertNull(DateUtil::toCarbonImmutable(null));
    }

    /**
     * 有効な日付文字列が提供された場合、toCarbonImmutableがCarbonImmutableオブジェクトを返すことをテスト
     * @param string $date
     * @testWith ["2024-03-24 12:30:45"]
     */
    public function testToCarbonImmutableReturnsCarbonImmutableIfValidDateStringIsProvided(string $date): void
    {
        $this->assertInstanceOf(CarbonImmutable::class, DateUtil::toCarbonImmutable($date));
    }

    /**
     * 年月のみが提供された場合、toCarbonImmutableが月初のCarbonImmutableオブジェクトを返すことをテスト
     * @param string $date
     * @testWith ["2024-03"]
     */
    public function testToCarbonImmutableReturnsCarbonImmutableWithDefaultDayIfOnlyYearAndMonthAreProvided(string $date): void
    {
        $carbon = DateUtil::toCarbonImmutable($date);
        $this->assertEquals('2024-03-01 00:00:00', $carbon->format('Y-m-d H:i:s'));
    }

    /**
     * 時間が含まれない場合、toCarbonImmutableが00:00:00を持つCarbonImmutableオブジェクトを返すことをテスト
     * @param string $date
     * @testWith ["2024-03-24"]
     */
    public function testToCarbonImmutableReturnsCarbonImmutableWithDefaultTimeIfNoTimeIsProvided(string $date): void
    {
        $carbon = DateUtil::toCarbonImmutable($date);
        $this->assertEquals('2024-03-24 00:00:00', $carbon->format('Y-m-d H:i:s'));
    }

    /**
     * 不正な日付文字列が提供された場合、toCarbonImmutableがnullを返すことをテスト
     */
    public function testToCarbonImmutableReturnsNullIfInvalidDateStringIsProvided(): void
    {
        $this->assertNull(DateUtil::toCarbonImmutable('invalid_date'));
    }

    /**
     * ハイフンが含まれる日付文字列が提供された場合、toCarbonImmutableがスラッシュでの日付変換を行うことをテスト
     * @param string $dateHyphen
     * @param string $dateSlash
     * @testWith ["2024-03-24 12:30:45", "2024/03/24 12:30:45"]
     */
    public function testToCarbonImmutableConvertsHyphenToSlashInDateString(string $dateHyphen, string $dateSlash): void
    {
        $carbonHyphen = DateUtil::toCarbonImmutable($dateHyphen);
        $carbonSlash = DateUtil::toCarbonImmutable($dateSlash);
        $this->assertEquals($carbonHyphen, $carbonSlash);
    }
}
