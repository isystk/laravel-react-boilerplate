<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StockTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('stocks')->insert([
            'name' => 'マカロン',
            'detail' => '厳選した素材で作ったマカロンです。',
            'price' => 2000,
            'imgpath' => 'makaron.jpg',
            'quantity' => 2,
        ]);

        DB::table('stocks')->insert([
            'name' => 'Bluetoothヘッドフォン',
            'detail' => 'ノイズキャンセリング機能搭載です。',
            'price' => 10000,
            'imgpath' => 'headphone.jpg',
            'quantity' => 5,
        ]);

        DB::table('stocks')->insert([
            'name' => '目覚まし時計',
            'detail' => '電波時計、オートスヌーズ機能搭載です。',
            'price' => 2000,
            'imgpath' => 'clock.jpg',
            'quantity' => 1,
        ]);

        DB::table('stocks')->insert([
            'name' => 'ドーナツ',
            'detail' => 'カロリーオフの美味しいドーナツです。',
            'price' => 120000,
            'imgpath' => 'donut.jpg',
            'quantity' => 99,
        ]);


        DB::table('stocks')->insert([
            'name' => '高級腕時計',
            'detail' => 'メンズ用の高級腕時計です。',
            'price' => 198000,
            'imgpath' => 'watch.jpg',
            'quantity' => 0,
        ]);

        DB::table('stocks')->insert([
            'name' => 'カメラレンズ35mm',
            'detail' => '最新式のカメラレンズです。',
            'price' => 79800,
            'imgpath' => 'lens.jpg',
            'quantity' => 10,
        ]);

        DB::table('stocks')->insert([
            'name' => 'シャンパン',
            'detail' => '香りと味わいのバランスが良いシャンパンです。',
            'price' => 800,
            'imgpath' => 'shanpan.jpg',
            'quantity' => 0,
        ]);

        DB::table('stocks')->insert([
            'name' => 'ビール',
            'detail' => '飲みやすいビールです。',
            'price' => 200,
            'imgpath' => 'beer.jpg',
            'quantity' => 100,
        ]);

        DB::table('stocks')->insert([
            'name' => '食パン',
            'detail' => '素材にこだわった美味しい食パンです。',
            'price' => 500,
            'imgpath' => 'pan.jpg',
            'quantity' => 8,
        ]);

        DB::table('stocks')->insert([
            'name' => 'スニーカー',
            'detail' => '軽くて履きやすいスニーカーです。',
            'price' => 3200,
            'imgpath' => 'sneaker.jpg',
            'quantity' => 3,
        ]);

        DB::table('stocks')->insert([
            'name' => 'デスクトップパソコン',
            'detail' => '最新OS搭載のデスクトップパソコンです。',
            'price' => 11200,
            'imgpath' => 'pc.jpg',
            'quantity' => 6,
        ]);

        DB::table('stocks')->insert([
            'name' => 'アコースティックギター',
            'detail' => '初心者向けのエントリーモデルです。',
            'price' => 25600,
            'imgpath' => 'aguiter.jpg',
            'quantity' => 9,
        ]);

        DB::table('stocks')->insert([
            'name' => 'エレキギター',
            'detail' => '初心者向けのエントリーモデルです。',
            'price' => 15600,
            'imgpath' => 'eguiter.jpg',
            'quantity' => 0,
        ]);

        DB::table('stocks')->insert([
            'name' => '麦わら帽子',
            'detail' => '夏にぴったりな麦わら帽子です。',
            'price' => 3200,
            'imgpath' => 'hat.jpg',
            'quantity' => 12,
        ]);

        DB::table('stocks')->insert([
            'name' => 'メガネ',
            'detail' => 'シンプルな黒縁メガネです。',
            'price' => 4200,
            'imgpath' => 'megane.jpg',
            'quantity' => 46,
        ]);

        DB::table('stocks')->insert([
            'name' => 'ロボット掃除機',
            'detail' => '自動で掃除してくれる便利な掃除機です。',
            'price' => 84200,
            'imgpath' => 'soujiki.jpg',
            'quantity' => 0,
        ]);
    }
}
