<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateFailedJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('失敗ジョブID');
            $table->text('connection')->comment('接続');
            $table->text('queue')->comment('キュー');
            $table->longText('payload')->comment('ペイロード');
            $table->longText('exception')->comment('例外');
            $table->timestamp('failed_at')->comment('失敗日時')->useCurrent();
        });
        DB::statement("ALTER TABLE failed_jobs COMMENT '失敗ジョブ'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('failed_jobs');
    }
}
