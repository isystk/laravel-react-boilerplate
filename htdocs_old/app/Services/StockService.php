<?php

namespace App\Services;

use App\Enums\ErrorType;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Utils\UploadImage;
use App\Repositories\StockRepository;

class StockService extends BaseService
{
    /**
     * @var StockRepository
     */
    protected StockRepository $stockRepository;

    public function __construct(
        Request         $request,
        StockRepository $stockRepository
    )
    {
        parent::__construct($request);
        $this->stockRepository = $stockRepository;
    }

    /**
     * @param int $limit
     * @return Collection|LengthAwarePaginator|array<string>
     */
    public function list(int $limit = 20): Collection|LengthAwarePaginator|array
    {
        return $this->stockRepository->findAll(
            $this->request()->name,
            [
                'limit' => $limit
            ]);
    }

    /**
     * @param string $stockId
     * @return object|null
     */
    public function find(string $stockId): object|null
    {
        return $this->stockRepository->find($stockId);
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
                    $model,
                    $stockId
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
            $result = $this->stockRepository->delete($id);

            DB::commit();
            return [$result, ErrorType::SUCCESS, null];
        } catch (\PDOException $e) {
            DB::rollBack();
            return [false, ErrorType::DATABASE, $e];
        } catch (\Exception $e) {
            DB::rollBack();
            return [false, ErrorType::FATAL, $e];
        }
    }
}
