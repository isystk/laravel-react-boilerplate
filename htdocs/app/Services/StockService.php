<?php

namespace App\Services;

use App\Enums\ErrorType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\Utils\UploadImage;
use App\Repositories\StockRepository;

class StockService extends Service
{
  /**
   * @var StockRepository
   */
  protected $stockRepository;

  public function __construct(
    Request $request,
    StockRepository $stockRepository
) {
    parent::__construct($request);
    $this->stockRepository = $stockRepository;
  }

  public function list($limit = 20)
  {
    return $this->stockRepository->findAll(
      $this->request()->name,
      [
      'limit'=>$limit
    ]);
  }

  public function find($stockId)
  {
    return $this->stockRepository->findById($stockId, []);
  }

  public function save($stockId=null)
  {

    // 画像ファイルを公開ディレクトリへ配置する。
    if ($this->request()->has('imageBase64') && $this->request()->imageBase64 !== null) {

      $tmpFile = UploadImage::converBase64($this->request()->imageBase64);
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

      if ($stockId) {
        // 変更

        $stock = $this->stockRepository->update(
          $stockId,
          $this->request()->input('name'),
          $this->request()->input('detail'),
          $this->request()->input('price'),
          $this->request()->input('quantity'),
          $fileName
        );

      } else {
        // 新規登録

        $stock = $this->stockRepository->store(
          null,
          $this->request()->input('name'),
          $this->request()->input('detail'),
          $this->request()->input('price'),
          $this->request()->input('quantity'),
          $fileName
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

  public function delete($id)
  {
    DB::beginTransaction();
    try {
        // 商品テーブルを削除
        $stock = $this->stockRepository->delete($id);

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
}
