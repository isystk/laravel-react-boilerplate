<?php

namespace App\Http\Requests\Admin;

use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

abstract class BaseImportRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * This method prepares file instante
     */
    protected function prepareForValidation(): void
    {
        $files = $this->file('upload_file') ?? [];
        if (!is_array($files)) {
            $files = [$files];
        }
        foreach ($files as $file) {
            $filePath = $file->path() ?: '';
            $mime = mime_content_type($filePath);
            if (!in_array($mime, ['text/plain','text/csv'],  true)) {
                // テキストファイル以外は何もしない
                return;
            }
            $tmp = file_get_contents($filePath) ?: '';
            $enc = mb_detect_encoding($tmp, ['ASCII', 'ISO-2022-JP', 'UTF-8', 'EUC-JP', 'SJIS'], true);
            if (false === $enc) {
                $enc = 'SJIS';
            }
            if ('UTF-8' !== $enc) {
                // UTF-8以外の文字コードでアップロードされた場合は、UTF-8に変換する
                $tmp = mb_convert_encoding($tmp, 'UTF-8', $enc);
            }
            $tmp = "\xef\xbb\xbf" . $tmp; // BOMを追加
            file_put_contents($filePath, $tmp);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<string|Closure>>
     */
    public function rules(): array
    {
        return [
            'upload_file' => [
                'required',
                'file',
                function($attribute, $value, $fail) {
                    $fileName = $value->getClientOriginalName();
                    $fileInfo = pathinfo($fileName);
                    $fileMimeType = mime_content_type($value->path());
                    $fileExtension = strtolower($fileInfo['extension']??'');
                    $fileSize = $value->getSize();
                    $upload_max_filesize = config('const.upload_max_filesize');
                    if (is_numeric($upload_max_filesize) && (int)$upload_max_filesize < $fileSize) {
                        $fail("ファイルには、".floor($upload_max_filesize/1000/1000)."MB以下のファイルを指定してください。");
                        return;
                    }
                    if (!in_array($fileExtension, $this->allowExtensions, true)) {
                        $fail($this->messages()['upload_file.*.mimes']);
                        return;
                    }
                    if ('application/encrypted' === $fileMimeType) {
                        $fail("ファイルがパスワードで保護されています。パスワードを解除してください。");
                        return;
                    }
                    if (!in_array($fileMimeType, $this->allowMimes, true)) {
                        $fail($this->messages()['upload_file.*.mimetypes']);
                    }
                },
            ],
        ];
    }

    /**
     * Get the validation after rules that apply to the request.
     * @return array<Closure>
     */
    public function after(): array
    {
       return [
            function (Validator $validator) {
                if (0 === count($validator->errors()->messages())) {
                    // rules に記載のエラーチェックに問題がなければ、ファイルの中身をチェックする
                    $import = $this->createImporter()($this->upload_file->path());
                    // ファイルの中身をチェックする
                    $errors = $import->validate();
                    if (0 < count($errors)) {
                        foreach ($errors as $error) {
                            $validator->errors()->add('upload_file',  $error);
                        }
                    }
                }
            }
        ];
    }

    /**
     * @return Closure
     */
    abstract protected function createImporter(): Closure;

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'upload_file.*' => 'ファイル',
        ];
    }

    /**
     * 定義済みバリデーションルールのエラーメッセージ取得
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'upload_file.required'  => 'ファイルは必須です',
            'upload_file.*.mimes'  => 'ファイルの拡張子がエクセル形式またはCSV形式ではありません',
            'upload_file.*.mimetypes'  => 'ファイルがエクセル形式またはCSV形式ではありません',
        ];
    }

    /**
     * 許容する拡張子
     * @var array|string[]
     */
    protected array $allowExtensions = ['csv','xlsx'];

    /**
     * 許容するファイル形式
     * @var array|string[]
     */
    protected array $allowMimes = ['text/plain','text/csv','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];

}
