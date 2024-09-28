<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('import_histories', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID');
            $table->unsignedBigInteger('job_id')->nullable()->comment('ジョブ管理テーブル（jobs）ID');
            $table->string('type', 32)->index()->comment('ジョブの種類');
            $table->string('file_name', 255)->comment('インポートファイル名');
            $table->tinyInteger('status')->default(0)->comment('ステータス');
            $table->unsignedBigInteger('import_user_id')->nullable()->comment('インポートしたユーザーID');
            $table->timestamp('import_at')->nullable()->comment('インポートした日時');
            $table->string('save_file_name', 255)->comment('サーバ上に格納されたファイル名');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('import_histories');
    }
};
