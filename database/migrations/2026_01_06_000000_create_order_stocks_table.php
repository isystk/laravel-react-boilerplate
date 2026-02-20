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
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->comment('注文ID');
            $table->foreignId('stock_id')->constrained('stocks')->comment('商品ID');
            $table->integer('price')->default(0)->comment('価格');
            $table->integer('quantity')->default(0)->comment('個数');
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
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
