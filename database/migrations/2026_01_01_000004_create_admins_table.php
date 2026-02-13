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
        Schema::create('admins', static function (Blueprint $table) { // ここを変更
            $table->increments('id')->comment('管理者ID');
            $table->string('name')->comment('管理者名');
            $table->string('email', 64)->unique()->comment('メールアドレス');
            $table->string('password')->comment('パスワード');
            $table->string('role')->default('manager')->comment('権限名');
            $table->rememberToken();
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
        });
        DB::statement("ALTER TABLE admins COMMENT '管理者'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
