<?php

namespace App\FileIO\Imports;

use App\Enums\AdminRole;
use Closure;
use Illuminate\Validation\Rules\Enum;

class StaffImport extends BaseImport
{
    protected array $attribute = [
        '名前'           => 'name',
        'メールアドレス' => 'email',
        '権限'           => 'role',
    ];

    /**
     * @param  array<int, mixed>      $row
     * @return array<string, ?string>
     */
    public function map($row): array
    {
        // 配列データをヘッダーをキーとした連想配列に変換します
        $rowMap = $this->getRowMap($row);

        $cell = (static fn (string $key, Closure $callback) => [$key => $callback($rowMap[$key] ?? null)]);

        return array_merge(
            $cell('name', $this->trimData(...)),
            $cell('email', $this->trimData(...)),
            $cell('role', $this->trimData(...)),
        );
    }

    /**
     * @return array<string, array<int, string|Enum|Closure>>
     */
    public function rules(): array
    {
        $maxlength = config('const.maxlength.admins');

        return [
            'name' => [
                'required',
                'string',
                'max:' . $maxlength['name'],
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:' . $maxlength['email'],
            ],
            'role' => [
                'required',
                new Enum(AdminRole::class),
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function customValidationMessages(): array
    {
        return [
            '*.Illuminate\Validation\Rules\Enum' => ':attributeの値が不正です。',
        ];
    }
}
