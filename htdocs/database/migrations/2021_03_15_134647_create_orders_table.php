<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->bigIncrements('id');
            $table->bigInteger('stock_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->integer('price')->nullable();
            $table->integer('quantity')->nullable();
            $table->timestamps();

            // 外部キーを追加
            $table->foreign('stock_id')->references('id')->on('stocks');

            // ↓これがあると何故かSeedでコケるのでコメントアウト
            // $table->foreign('user_id')->references('id')->on('users');
        });
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
