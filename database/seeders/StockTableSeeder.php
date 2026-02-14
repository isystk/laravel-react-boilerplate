<?php

namespace Database\Seeders;

use App\Domain\Entities\Image;
use App\Domain\Entities\Stock;
use App\Enums\PhotoType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class StockTableSeeder extends Seeder
{
    public function run(): void
    {
        $stocks = [
            ['name' => 'マカロン',              'detail' => '厳選した素材で作ったマカロンです。',               'price' => 2000,   'image' => 'makaron.jpg',  'quantity' => 2],
            ['name' => 'Bluetoothヘッドフォン',  'detail' => 'ノイズキャンセリング機能搭載です。',               'price' => 10000,  'image' => 'headphone.jpg', 'quantity' => 5],
            ['name' => '目覚まし時計',           'detail' => '電波時計、オートスヌーズ機能搭載です。',           'price' => 2000,   'image' => 'clock.jpg',    'quantity' => 1],
            ['name' => 'ドーナツ',               'detail' => 'カロリーオフの美味しいドーナツです。',             'price' => 120000, 'image' => 'donut.jpg',    'quantity' => 99],
            ['name' => '高級腕時計',             'detail' => 'メンズ用の高級腕時計です。',                       'price' => 198000, 'image' => 'watch.jpg',    'quantity' => 0],
            ['name' => 'カメラレンズ35mm',       'detail' => '最新式のカメラレンズです。',                       'price' => 79800,  'image' => 'lens.jpg',     'quantity' => 10],
            ['name' => 'シャンパン',             'detail' => '香りと味わいのバランスが良いシャンパンです。',     'price' => 800,    'image' => 'shanpan.jpg',  'quantity' => 0],
            ['name' => 'ビール',                 'detail' => '飲みやすいビールです。',                           'price' => 200,    'image' => 'beer.jpg',     'quantity' => 100],
            ['name' => '食パン',                 'detail' => '素材にこだわった美味しい食パンです。',             'price' => 500,    'image' => 'pan.jpg',      'quantity' => 8],
            ['name' => 'スニーカー',             'detail' => '軽くて履きやすいスニーカーです。',                 'price' => 3200,   'image' => 'sneaker.jpg',  'quantity' => 3],
            ['name' => 'デスクトップパソコン',    'detail' => '最新OS搭載のデスクトップパソコンです。',           'price' => 11200,  'image' => 'pc.jpg',       'quantity' => 6],
            ['name' => 'アコースティックギター',  'detail' => '初心者向けのエントリーモデルです。',               'price' => 25600,  'image' => 'aguiter.jpg',  'quantity' => 9],
            ['name' => 'エレキギター',           'detail' => '初心者向けのエントリーモデルです。',               'price' => 15600,  'image' => 'eguiter.jpg',  'quantity' => 0],
            ['name' => '麦わら帽子',             'detail' => '夏にぴったりな麦わら帽子です。',                   'price' => 3200,   'image' => 'hat.jpg',      'quantity' => 12],
            ['name' => 'メガネ',                 'detail' => 'シンプルな黒縁メガネです。',                       'price' => 4200,   'image' => 'megane.jpg',   'quantity' => 46],
            ['name' => 'ロボット掃除機',         'detail' => '自動で掃除してくれる便利な掃除機です。',           'price' => 84200,  'image' => 'soujiki.jpg',  'quantity' => 0],
        ];

        foreach ($stocks as $stockData) {
            $image = Image::create([
                'file_name' => $stockData['image'],
                'type'      => PhotoType::Stock->value,
            ]);

            Storage::disk('s3')->putFileAs(
                $image->type->type() . '/' . $image->getHashedDirectory(),
                Storage::path('stocks' . '/' . $stockData['image']),
                $stockData['image']
            );

            Stock::create([
                'name'     => $stockData['name'],
                'detail'   => $stockData['detail'],
                'price'    => $stockData['price'],
                'quantity' => $stockData['quantity'],
                'image_id' => $image->id,
            ]);
        }
    }
}
