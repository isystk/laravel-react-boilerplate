<?php

namespace Database\Seeders;

use App\Domain\Entities\Stock;
use Illuminate\Database\Seeder;

class StockTableSeeder extends Seeder
{
    public function run(): void
    {
        Stock::create([
            'name' => 'マカロン',
            'detail' => '厳選した素材で作ったマカロンです。',
            'price' => 2000,
            'image_file_name' => 'makaron.jpg',
            'quantity' => 2,
        ]);

        Stock::create([
            'name' => 'Bluetoothヘッドフォン',
            'detail' => 'ノイズキャンセリング機能搭載です。',
            'price' => 10000,
            'image_file_name' => 'headphone.jpg',
            'quantity' => 5,
        ]);

        Stock::create([
            'name' => '目覚まし時計',
            'detail' => '電波時計、オートスヌーズ機能搭載です。',
            'price' => 2000,
            'image_file_name' => 'clock.jpg',
            'quantity' => 1,
        ]);

        Stock::create([
            'name' => 'ドーナツ',
            'detail' => 'カロリーオフの美味しいドーナツです。',
            'price' => 120000,
            'image_file_name' => 'donut.jpg',
            'quantity' => 99,
        ]);

        Stock::create([
            'name' => '高級腕時計',
            'detail' => 'メンズ用の高級腕時計です。',
            'price' => 198000,
            'image_file_name' => 'watch.jpg',
            'quantity' => 0,
        ]);

        Stock::create([
            'name' => 'カメラレンズ35mm',
            'detail' => '最新式のカメラレンズです。',
            'price' => 79800,
            'image_file_name' => 'lens.jpg',
            'quantity' => 10,
        ]);

        Stock::create([
            'name' => 'シャンパン',
            'detail' => '香りと味わいのバランスが良いシャンパンです。',
            'price' => 800,
            'image_file_name' => 'shanpan.jpg',
            'quantity' => 0,
        ]);

        Stock::create([
            'name' => 'ビール',
            'detail' => '飲みやすいビールです。',
            'price' => 200,
            'image_file_name' => 'beer.jpg',
            'quantity' => 100,
        ]);

        Stock::create([
            'name' => '食パン',
            'detail' => '素材にこだわった美味しい食パンです。',
            'price' => 500,
            'image_file_name' => 'pan.jpg',
            'quantity' => 8,
        ]);

        Stock::create([
            'name' => 'スニーカー',
            'detail' => '軽くて履きやすいスニーカーです。',
            'price' => 3200,
            'image_file_name' => 'sneaker.jpg',
            'quantity' => 3,
        ]);

        Stock::create([
            'name' => 'デスクトップパソコン',
            'detail' => '最新OS搭載のデスクトップパソコンです。',
            'price' => 11200,
            'image_file_name' => 'pc.jpg',
            'quantity' => 6,
        ]);

        Stock::create([
            'name' => 'アコースティックギター',
            'detail' => '初心者向けのエントリーモデルです。',
            'price' => 25600,
            'image_file_name' => 'aguiter.jpg',
            'quantity' => 9,
        ]);

        Stock::create([
            'name' => 'エレキギター',
            'detail' => '初心者向けのエントリーモデルです。',
            'price' => 15600,
            'image_file_name' => 'eguiter.jpg',
            'quantity' => 0,
        ]);

        Stock::create([
            'name' => '麦わら帽子',
            'detail' => '夏にぴったりな麦わら帽子です。',
            'price' => 3200,
            'image_file_name' => 'hat.jpg',
            'quantity' => 12,
        ]);

        Stock::create([
            'name' => 'メガネ',
            'detail' => 'シンプルな黒縁メガネです。',
            'price' => 4200,
            'image_file_name' => 'megane.jpg',
            'quantity' => 46,
        ]);

        Stock::create([
            'name' => 'ロボット掃除機',
            'detail' => '自動で掃除してくれる便利な掃除機です。',
            'price' => 84200,
            'image_file_name' => 'soujiki.jpg',
            'quantity' => 0,
        ]);
    }
}
