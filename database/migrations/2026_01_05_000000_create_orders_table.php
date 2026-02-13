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
        Schema::create('orders', static function (Blueprint $table) {
            $table->bigIncrements('id')->comment('注文ID');
            $table->unsignedBigInteger('user_id')->comment('ユーザID');
            $table->integer('sum_price')->default(0)->comment('合計金額');
            $table->dateTime('created_at');
            $table->dateTime('updated_at');

            // 外部キーを追加
            $table->foreign('user_id')->references('id')->on('users');
        });
        DB::statement("ALTER TABLE orders COMMENT '注文'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 外部キーを削除
        Schema::table('orders', static function (Blueprint $table) {
            $table->dropForeign('orders_user_id_foreign');
        });
        // テーブルを削除
        Schema::dropIfExists('orders');
    }
};
