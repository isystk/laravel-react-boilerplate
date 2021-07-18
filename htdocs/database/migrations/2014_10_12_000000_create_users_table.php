<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ユーザID');
            $table->string('name')->comment('ユーザ名');
            $table->string('email')->comment('メールアドレス')->unique();
            $table->timestamp('email_verified_at')->comment('メール検証日時')->nullable();
            $table->string('password')->comment('パスワード');
            $table->rememberToken();
            $table->timestamps();
        });
        DB::statement("ALTER TABLE users COMMENT 'ユーザ'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
