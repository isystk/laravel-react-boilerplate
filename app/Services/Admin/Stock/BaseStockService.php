<?php

namespace App\Services\Admin\Stock;

use App\Services\BaseService;
use Illuminate\Http\Request;

class BaseStockService extends BaseService
{
    /**
     * リクエストパラメータから検索条件に変換します。
     *
     * @return array{
     *   name : ?string,
     *   sort_name : string,
     *   sort_direction : 'asc' | 'desc',
     *   limit : int,
     * }
     */
    public function convertConditionsFromRequest(Request $request, int $limit = 20): array
    {
        $conditions = [
            'name'           => null,
            'email'          => null,
            'role'           => null,
            'sort_name'      => $request->sort_name ?? 'updated_at',
            'sort_direction' => $request->sort_direction ?? 'desc',
            'limit'          => $limit,
        ];

        if ($request->name !== null) {
            $conditions['name'] = $request->name;
        }

        return $conditions;
    }
}
