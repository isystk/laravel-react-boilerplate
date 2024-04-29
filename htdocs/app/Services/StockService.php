<?php

namespace App\Services;

use App\Domain\Repositories\Stock\StockRepository;
use App\Enums\ErrorType;
use App\Utils\UploadImage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockService extends BaseService
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
     * @param int $limit
     * @return Collection|LengthAwarePaginator
     */
    public function list(int $limit = 20): Collection|LengthAwarePaginator
    {
        return $this->stockRepository->findAll(
            $this->request()->name,
            [
                'limit' => $limit,
            ]);
    }

    /**
     * @param int $stockId
     * @return object|null
     */
    public function find(int $stockId): object|null
    {
        return $this->stockRepository->findById($stockId);
    }

    /**
     * @param int|null $stockId
     * @return array<string>
     */
    public function save(int $stockId = null): array
    {
        // 画像ファイルを公開ディレクトリへ配置する。
        if ($this->request()->has('imageBase64') && $this->request()->imageBase64 !== null) {
            $tmpFile = UploadImage::convertBase64($this->request()->imageBase64);
            $fileName = $this->request()->fileName;

            //s3に画像をアップロード
            $tmpFile->storeAs('stock', $fileName);

            // //ストレージにも画像を保存
            // $target_path = storage_path('uploads/stock/');
            // $tmpFile->move($target_path, $fileName);
        } else {
            $fileName = "";
        }

        DB::beginTransaction();
        try {
            $model = [
                'name' => $this->request()->input('name'),
                'detail' => $this->request()->input('detail'),
                'price' => $this->request()->input('price'),
                'quantity' => $this->request()->input('quantity'),
            ];
            if (!empty($fileName)) {
                $model['imgpath'] = $fileName;
            }

            if ($stockId) {
                // 変更

                $stock = $this->stockRepository->update(
                    $stockId,
                    $model
                );
            } else {
                // 新規登録

                $stock = $this->stockRepository->create(
                    $model
                );

                $id = $stock->id;
            }

            DB::commit();

            return [$stock, ErrorType::SUCCESS, null];
        } catch (\PDOException $e) {
            DB::rollBack();
            return [false, ErrorType::DATABASE, $e];
        } catch (\Exception $e) {
            DB::rollBack();
            return [false, ErrorType::FATAL, $e];
        }
    }

    /**
     * @param int $id
     * @return array<mixed|\App\Enums\ErrorType>
     */
    public function delete(int $id): array
    {
        DB::beginTransaction();
        try {
            // 商品テーブルを削除
            $this->stockRepository->delete($id);

            DB::commit();
            return [true, ErrorType::SUCCESS, null];
        } catch (\PDOException $e) {
            DB::rollBack();
            return [false, ErrorType::DATABASE, $e];
        } catch (\Exception $e) {
            DB::rollBack();
            return [false, ErrorType::FATAL, $e];
        }
    }
}
