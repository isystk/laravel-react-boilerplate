<?php

namespace App\Domain\Repositories\Image;

use App\Domain\Entities\Image;
use App\Domain\Repositories\BaseEloquentRepository;

class ImageEloquentRepository extends BaseEloquentRepository implements ImageRepository
{
    protected function model(): string
    {
        return Image::class;
    }
}
