<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePasswordResetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->comment('メールアドレス')->index();
            $table->string('token')->comment('ワンタイムトークン');
            $table->timestamp('created_at')->comment('登録日時')->nullable();
        });
        DB::statement("ALTER TABLE password_resets COMMENT 'パスワードリセット'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('password_resets');
    }
}
