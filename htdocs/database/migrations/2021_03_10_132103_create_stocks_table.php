<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('商品ID');
            $table->timestamps();
            $table->string('name', '100')->comment('商品名');
            $table->string('detail', '500')->comment('説明文');
            $table->integer('price')->comment('価格');
            $table->string('imgpath', '200')->comment('画像ファイル名');
        });
        DB::statement("ALTER TABLE stocks COMMENT '商品'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stocks');
    }
}
