<?php

namespace App\Domain\Concerns;

use DateTimeInterface;
use Illuminate\Support\Carbon;

trait SerializesJstTimestamps
{
    /**
     * created_at / updated_at / deleted_at を JST でシリアライズする
     *
     * @return array<string, mixed>
     */
    public function attributesToArray(): array
    {
        $attributes = parent::attributesToArray();

        foreach (['created_at', 'updated_at', 'deleted_at'] as $key) {
            $rawValue = $this->getRawOriginal($key);
            if ($rawValue === null) {
                continue;
            }

            $attributes[$key] = Carbon::parse((string) $rawValue, config('app.timezone'))->format(DateTimeInterface::ATOM);
        }

        return $attributes;
    }
}
