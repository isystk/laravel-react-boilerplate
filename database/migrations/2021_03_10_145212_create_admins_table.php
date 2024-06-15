<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
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
        Schema::create('admins', static function (Blueprint $table) { //ここを変更
            $table->increments('id')->comment('管理者ID');
            $table->string('name')->comment('管理者名');
            $table->string('email', 64)->unique()->comment('メールアドレス');
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
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
