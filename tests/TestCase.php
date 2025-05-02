<?php

namespace Tests;

use App\Domain\Entities\Admin;
use App\Domain\Entities\Cart;
use App\Domain\Entities\ContactForm;
use App\Domain\Entities\ContactFormImage;
use App\Domain\Entities\ImportHistory;
use App\Domain\Entities\MonthlySale;
use App\Domain\Entities\Order;
use App\Domain\Entities\OrderStock;
use App\Domain\Entities\Stock;
use App\Domain\Entities\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

abstract class TestCase extends BaseTestCase
{

    /**
     * private protected 関数 をリフレクションでアクセス可能にする
     * @param object $target
     * @param string $methodName
     * @return ReflectionMethod
     * @throws ReflectionException
     */
    protected function setPrivateMethodTest(object $target, string $methodName): ReflectionMethod
    {
        $reflection = new ReflectionClass($target);
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method;
    }

    /**
     * private protected プロパティ をリフレクションでアクセス可能にする
     * @param object $target
     * @param string $propertyName
     * @return mixed
     * @throws ReflectionException
     */
    protected function getPrivateProperty(object $target, string $propertyName): mixed
    {
        $reflection = new ReflectionClass($target);
        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);

        return $property->getValue($target);
    }

    /**
     * 指定したコマンドとオプションでバッチ処理を実行します。
     * @param string $command
     * @param array<string, mixed> $options
     * @return void
     */
    protected function runBatch(string $command, array $options): void
    {
        // 標準出力を一時的に無効にする
        ob_start();

        // バッチ処理の実行
        $this->artisan($command, $options)
            ->assertExitCode(0);

        // 標準出力を無効にした状態を解除し、出力バッファを閉じる
        ob_end_clean();
    }

    /**
     * CSVファイルを読み込んで配列データを返却します。
     * @param string $path
     * @return array<array<string>>
     */
    protected function readCsv(string $path): array
    {
        $rows = [];
        $csvFile = fopen($path, 'rb');
        if ($csvFile === false) {
            return $rows;
        }

        // SJIS-winからUTF-8にエンコード
//        stream_filter_append($csvFile, 'convert.iconv.SJIS-win/UTF-8');
        // BOMを削除する
        $bom = pack('H*', 'EFBBBF');

        $first3bytes = fread($csvFile, 3);
        if ($first3bytes !== $bom) {
            rewind($csvFile);
        }

        while (($row = fgetcsv($csvFile)) !== false) {
            $rows[] = $row;
        }

        fclose($csvFile);
        return $rows;
    }

    /**
     * Userを作成する
     * @param array<string, mixed> $params
     * @return User
     */
    public function createDefaultUser(array $params = []): User
    {
        $items = [];
        if (0 < count($params)) {
            $items = array_merge($items, $params);
        }
        /** @var User */
        return User::factory($items)->create();
    }

    /**
     * Adminを作成する
     * @param array<string, mixed> $params
     * @return Admin
     */
    public function createDefaultAdmin(array $params = []): Admin
    {
        $items = [];
        if (0 < count($params)) {
            $items = array_merge($items, $params);
        }
        /** @var Admin */
        return Admin::factory($items)->create();
    }

    /**
     * Stockを作成する
     * @param array<string, mixed> $params
     * @return Stock
     */
    public function createDefaultStock(array $params = []): Stock
    {
        $items = [];
        if (0 < count($params)) {
            $items = array_merge($items, $params);
        }
        /** @var Stock */
        return Stock::factory($items)->create();
    }

    /**
     * Cartを作成する
     * @param array<string, mixed> $params
     * @return Cart
     */
    public function createDefaultCart(array $params = []): Cart
    {
        $user = $this->createDefaultUser();
        $stock = $this->createDefaultStock();
        $items = [
            'user_id' => $user->id,
            'stock_id' => $stock->id,
        ];
        if (0 < count($params)) {
            $items = array_merge($items, $params);
        }
        /** @var Cart */
        return Cart::factory($items)->create();
    }

    /**
     * Orderを作成する
     * @param array<string, mixed> $params
     * @return Order
     */
    public function createDefaultOrder(array $params = []): Order
    {
        $user = $this->createDefaultUser();
        $items = [
            'user_id' => $user->id,
        ];
        if (0 < count($params)) {
            $items = array_merge($items, $params);
        }
        /** @var Order */
        return Order::factory($items)->create();
    }

    /**
     * OrderStockを作成する
     * @param array<string, mixed> $params
     * @return OrderStock
     */
    public function createDefaultOrderStock(array $params = []): OrderStock
    {
        $order = $this->createDefaultOrder();
        $stock = $this->createDefaultStock();
        $items = [
            'order_id' => $order->id,
            'stock_id' => $stock->id,
            'price' => 1000,
            'quantity' => 1,
        ];
        if (0 < count($params)) {
            $items = array_merge($items, $params);
        }
        /** @var OrderStock */
        return OrderStock::factory($items)->create();
    }

    /**
     * ContactFormを作成する
     * @param array<string, mixed> $params
     * @return ContactForm
     */
    public function createDefaultContactForm(array $params = []): ContactForm
    {
        $items = [];
        if (0 < count($params)) {
            $items = array_merge($items, $params);
        }
        /** @var ContactForm */
        return ContactForm::factory($items)->create();
    }

    /**
     * ContactFormImageを作成する
     * @param array<string, mixed> $params
     * @return ContactFormImage
     */
    public function createDefaultContactFormImage(array $params = []): ContactFormImage
    {
        $contactForm = $this->createDefaultContactForm();
        $items = [
            'contact_form_id' => $contactForm->id,
        ];
        if (0 < count($params)) {
            $items = array_merge($items, $params);
        }
        /** @var ContactFormImage */
        return ContactFormImage::factory($items)->create();
    }

    /**
     * ImportHistoryを作成する
     * @param array<string, mixed> $params
     * @return ImportHistory
     */
    public function createDefaultImportHistory(array $params = []): ImportHistory
    {
        $items = [];
        if (0 < count($params)) {
            $items = array_merge($items, $params);
        }
        /** @var ImportHistory */
        return ImportHistory::factory($items)->create();
    }

    /**
     * MonthlySaleを作成する
     * @param array<string, mixed> $params
     * @return MonthlySale
     */
    public function createDefaultMonthlySale(array $params = []): MonthlySale
    {
        $items = [];
        if (0 < count($params)) {
            $items = array_merge($items, $params);
        }
        /** @var MonthlySale */
        return MonthlySale::factory($items)->create();
    }
}
