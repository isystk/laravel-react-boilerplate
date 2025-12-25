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
        Schema::create('contact_forms', static function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('user_name', 20)->comment('お名前');
            $table->string('title', 50)->comment('タイトル');
            $table->string('email', 64)->comment('メールアドレス');
            $table->longText('url')->comment('URL')->nullable($value = true);
            $table->tinyInteger('gender')->comment('性別');
            $table->tinyInteger('age')->comment('年齢');
            $table->string('contact', 200)->comment('お問い合わせ内容');
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
        });
        DB::statement("ALTER TABLE contact_forms COMMENT 'お問い合わせ'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_forms');
    }
};
