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
        Schema::create('stocks', static function (Blueprint $table) {
            $table->bigIncrements('id')->comment('商品ID');
            $table->timestamps();
            $table->string('name', '100')->comment('商品名');
            $table->string('detail', '500')->comment('説明文');
            $table->integer('price')->default(0)->comment('価格');
            $table->string('imgpath', '200')->comment('画像ファイル名');
            $table->integer('quantity')->default(0)->comment('在庫数');
        });
        DB::statement("ALTER TABLE stocks COMMENT '商品'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
