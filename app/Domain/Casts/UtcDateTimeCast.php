<?php

namespace App\Domain\Casts;

use DateTimeInterface;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Contracts\Database\Eloquent\SerializesCastableAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @implements CastsAttributes<?Carbon, mixed>
 */
class UtcDateTimeCast implements CastsAttributes, SerializesCastableAttributes
{
    private const TIMEZONE = 'UTC';

    public bool $withoutObjectCaching = true;

    /**
     * @param array<string, mixed> $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?Carbon
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof Carbon) {
            return $value->copy()->setTimezone(self::TIMEZONE);
        }

        if ($value instanceof DateTimeInterface) {
            return Carbon::instance($value)->setTimezone(self::TIMEZONE);
        }

        return Carbon::parse((string) $value, self::TIMEZONE);
    }

    /**
     * @param array<string, mixed> $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof Carbon) {
            return $value->copy()->setTimezone(self::TIMEZONE)->format('Y-m-d H:i:s');
        }

        if ($value instanceof DateTimeInterface) {
            return Carbon::instance($value)->setTimezone(self::TIMEZONE)->format('Y-m-d H:i:s');
        }

        if (is_numeric($value)) {
            return Carbon::createFromTimestamp((int) $value, self::TIMEZONE)->format('Y-m-d H:i:s');
        }

        return Carbon::parse((string) $value, self::TIMEZONE)->format('Y-m-d H:i:s');
    }

    /**
     * @param array<string, mixed> $attributes
     */
    public function serialize(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if ($value === null) {
            return null;
        }

        return $this->get($model, $key, $value, $attributes)?->format(DateTimeInterface::ATOM);
    }
}
