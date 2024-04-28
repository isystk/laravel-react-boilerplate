<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateAdminsTable extends Migration //ここを変更
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) { //ここを変更
            $table->increments('id')->comment('管理者ID');
            $table->string('name')->comment('管理者名');
            $table->string('email')->comment('メールアドレス')->unique();
            $table->string('password')->comment('パスワード');
            $table->rememberToken();
            $table->timestamps();
        });
        DB::statement("ALTER TABLE admins COMMENT '管理者'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
