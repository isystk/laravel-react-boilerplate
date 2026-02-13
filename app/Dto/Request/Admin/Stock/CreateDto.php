<?php

namespace App\Dto\Request\Admin\Stock;

use App\Http\Requests\Admin\Stock\StoreRequest;
use App\Utils\UploadImage;
use Illuminate\Http\UploadedFile;

class CreateDto
{
    // 名前
    public ?string $name;

    // 詳細
    public ?string $detail;

    // 価格
    public ?int $price;

    // 在庫数
    public ?int $quantity;

    // 画像ファイル名
    public ?string $imageFileName;

    // 画像のBase64データ
    public ?UploadedFile $imageFile;

    public function __construct(
        StoreRequest $request
    ) {
        $this->name          = (string) $request->input('name');
        $this->detail        = (string) $request->input('detail');
        $this->price         = (int) $request->input('price');
        $this->quantity      = (int) $request->input('quantity');
        $this->imageFileName = (string) $request->input('image_file_name');
        // imageBase64パラメータがあればUploadedFileオブジェクトに変換してimageFileパラメータに上書きする。
        $this->imageFile = null;
        if ($request->has('image_base_64') && $request->image_base_64) {
            $this->imageFile = UploadImage::convertBase64($request->image_base_64);
        }
    }
}
