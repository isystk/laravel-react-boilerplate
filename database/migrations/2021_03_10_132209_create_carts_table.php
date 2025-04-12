<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('carts', static function (Blueprint $table) {
            $table->bigIncrements('id')->comment('カートID');
            $table->unsignedBigInteger('stock_id')->comment('商品ID');
            $table->unsignedBigInteger('user_id')->comment('ユーザID');
            $table->timestamps();

            // 外部キーを追加
            $table->foreign('stock_id')->references('id')->on('stocks');
            $table->foreign('user_id')->references('id')->on('users');

        });
        DB::statement("ALTER TABLE carts COMMENT 'カート'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
