<?php

namespace App\Domain\Repositories\Admin;

use App\Domain\Entities\Admin;
use App\Domain\Repositories\BaseEloquentRepository;

class AdminEloquentRepository extends BaseEloquentRepository implements AdminRepository
{

    /**
     * @return string
     */
    protected function model(): string
    {
        return Admin::class;
    }

}
