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
    /**
     * @var StockRepository
     */
    protected StockRepository $stockRepository;

    public function __construct(
        Request $request,
        StockRepository $stockRepository
    )
    {
        parent::__construct($request);
        $this->stockRepository = $stockRepository;
    }

    /**
     * @param int $stockId
     * @return Stock
     */
    public function update(int $stockId): Stock
    {
        $fileName = $this->request()->fileName;

        $model = [
            'name' => $this->request()->input('name'),
            'detail' => $this->request()->input('detail'),
            'price' => $this->request()->input('price'),
            'quantity' => $this->request()->input('quantity'),
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
            $tmpFile = UploadImage::convertBase64($this->request()->imageBase64);
            $tmpFile->storeAs(PhotoType::Stock->dirName(), $fileName);
        }

        return $stock;
    }

}
