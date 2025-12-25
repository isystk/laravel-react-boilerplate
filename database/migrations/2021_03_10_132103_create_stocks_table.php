<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stocks', static function (Blueprint $table) {
            $table->bigIncrements('id')->comment('商品ID');
            $table->string('name', '100')->comment('商品名');
            $table->string('detail', '500')->comment('説明文');
            $table->integer('price')->default(0)->comment('価格');
            $table->string('image_file_name', '200')->comment('画像ファイル名');
            $table->integer('quantity')->default(0)->comment('在庫数');
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
        });
        DB::statement("ALTER TABLE stocks COMMENT '商品'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
