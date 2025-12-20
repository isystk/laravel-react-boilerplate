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
        Schema::create('monthly_sales', static function (Blueprint $table) {
            $table->bigIncrements('id')->comment('月別売上ID');
            $table->string('year_month', '6')->comment('年月');
            $table->integer('order_count')->default(0)->comment('注文数');
            $table->integer('amount')->default(0)->comment('売上金額');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE monthly_sales COMMENT '月別売上'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_sales');
    }
};
