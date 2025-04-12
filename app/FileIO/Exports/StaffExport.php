<?php

namespace App\FileIO\Exports;

use App\Domain\Entities\Admin;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StaffExport implements FromCollection, WithHeadings
{
    /** @var Collection<int, Admin> $admins */
    protected Collection $admins;

    /**
     * コンストラクタ
     *
     * @param Collection<int, Admin> $admins 管理者のコレクション
     */
    public function __construct(Collection $admins)
    {
        $this->admins = $admins;
    }

    /**
     * 管理者のデータをコレクションとして返します。
     *
     * @return Collection<array> エクスポート用にフォーマットされた管理者のコレクション
     */
    public function collection(): Collection
    {
        return $this->admins->map(function ($admin) {
            return [
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
                'role' => $admin->role,
            ];
        });
    }

    /**
     * エクスポートファイルのヘッダーを返します。
     *
     * @return string[] エクスポートファイルのヘッダー
     */
    public function headings(): array
    {
        return [
            'ID',
            '名前',
            'メールアドレス',
            '権限'
        ];
    }

}
