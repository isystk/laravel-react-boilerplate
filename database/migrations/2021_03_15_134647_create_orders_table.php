<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('注文ID');
            $table->bigInteger('stock_id')->comment('商品ID')->unsigned();
            $table->bigInteger('user_id')->comment('ユーザID')->unsigned();
            $table->integer('price')->comment('価格')->nullable();
            $table->integer('quantity')->comment('個数')->nullable();
            $table->timestamps();

            // 外部キーを追加
            $table->foreign('stock_id')->references('id')->on('stocks');

            // ↓これがあると何故かSeedでコケるのでコメントアウト
            // $table->foreign('user_id')->references('id')->on('users');
        });
        DB::statement("ALTER TABLE orders COMMENT '注文'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // 外部キーを削除
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign('orders_stock_id_foreign');
            // ↓これがあると何故かSeedでコケるのでコメントアウト
            // $table->dropForeign('orders_user_id_foreign');
        });
        // テーブルを削除
        Schema::dropIfExists('orders');
    }
}
