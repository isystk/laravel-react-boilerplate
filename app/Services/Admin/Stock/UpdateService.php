<?php

namespace App\Services\Admin\Stock;

use App\Domain\Entities\Stock;
use App\Domain\Repositories\Stock\StockRepository;
use App\Enums\PhotoType;
use App\Services\BaseService;
use App\Utils\UploadImage;
use Illuminate\Http\Request;

class UpdateService extends BaseService
{

    private StockRepository $stockRepository;

    /**
     * Create a new controller instance.
     *
     * @param StockRepository $stockRepository
     */
    public function __construct(
        StockRepository $stockRepository
    )
    {
        $this->stockRepository = $stockRepository;
    }

    /**
     * 商品を更新します。
     * @param int $stockId
     * @param Request $request
     * @return Stock
     */
    public function update(int $stockId, Request $request): Stock
    {
        $fileName = $request->fileName;

        $model = [
            'name' => $request->input('name'),
            'detail' => $request->input('detail'),
            'price' => $request->input('price'),
            'quantity' => $request->input('quantity'),
        ];
        if (!empty($fileName)) {
            $model['imgpath'] = $fileName;
        }

        $stock = $this->stockRepository->update(
            $stockId,
            $model
        );

        if (null !== $fileName) {
            //s3に画像をアップロード
            $tmpFile = UploadImage::convertBase64($request->imageBase64);
            $tmpFile->storeAs(PhotoType::Stock->dirName(), $fileName);
        }

        return $stock;
    }

}
