<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', static function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('表示名');
            $table->string('email')->unique()->comment('メールアドレス');
            $table->dateTime('email_verified_at')->nullable()->comment('メールアドレス確認日時');
            $table->string('password')->nullable()->comment('パスワード');
            $table->unsignedBigInteger('avatar_image_id')->nullable()->comment('アバター画像ID');
            $table->string('google_id')->nullable()->unique()->comment('GoogleアカウントID(OAuthログイン専用)');
            $table->string('avatar_url')->nullable()->comment('アバターURL(OAuthログイン専用)');
            $table->rememberToken();
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
        });

        Schema::create('password_reset_tokens', static function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->dateTime('created_at');
        });

        Schema::create('sessions', static function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
