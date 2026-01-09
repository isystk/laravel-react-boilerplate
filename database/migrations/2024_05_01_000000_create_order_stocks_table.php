<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_stocks', static function (Blueprint $table) {
            $table->bigIncrements('id')->comment('注文商品ID');
            $table->unsignedBigInteger('order_id')->comment('注文ID');
            $table->unsignedBigInteger('stock_id')->comment('商品ID');
            $table->integer('price')->default(0)->comment('価格');
            $table->integer('quantity')->default(0)->comment('個数');
            $table->dateTime('created_at');
            $table->dateTime('updated_at');

            // 外部キーを追加
            $table->foreign('order_id')->references('id')->on('orders');
            $table->foreign('stock_id')->references('id')->on('stocks');
        });
        DB::statement("ALTER TABLE order_stocks COMMENT '注文商品'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 外部キーを削除
        Schema::table('order_stocks', static function (Blueprint $table) {
            $table->dropForeign('order_stocks_stock_id_foreign');
        });
        // テーブルを削除
        Schema::dropIfExists('order_stocks');
    }
};
