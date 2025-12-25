<?php

namespace App\Services\Commands;

use App\Services\BaseService;
use Illuminate\Support\Facades\Validator;

class PhotoS3UploadService extends BaseService
{
    /**
     * 引数の入力チェックを行い、問題がある場合はエラーメッセージを返却する
     * @param array<string, mixed> $data
     * @return string[] エラーメッセージの配列
     */
    public function validateArgs(array $data): array
    {
        $rules = [
            'file_name' => [
                'nullable',
                'string',
                'max:32',
            ],
        ];
        $messages = [];
        $attributes = [
            'file_name' => 'ファイル名(--file_name)',
        ];
        $validator = Validator::make($data, $rules, $messages, $attributes);
        return $validator->errors()->all();
    }
}
