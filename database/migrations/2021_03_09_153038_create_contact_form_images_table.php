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
        Schema::create('contact_form_images', static function (Blueprint $table) {
            $table->bigIncrements('id')->comment('お問い合わせ画像ID');
            $table->unsignedBigInteger('contact_form_id')->comment('お問い合わせID');
            $table->string('file_name', 100)->comment('ファイル名');
            $table->dateTime('created_at');
            $table->dateTime('updated_at');

            // 外部キー制約
            $table->foreign('contact_form_id')->references('id')->on('contact_forms');
        });
        DB::statement("ALTER TABLE contact_form_images COMMENT 'お問い合わせ画像'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_form_images');
    }
};
