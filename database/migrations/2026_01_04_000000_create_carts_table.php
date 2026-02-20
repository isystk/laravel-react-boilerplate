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
        Schema::create('carts', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_id')->constrained('stocks')->comment('商品ID');
            $table->foreignId('user_id')->constrained('users')->comment('ユーザID');
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
        });
        DB::statement("ALTER TABLE carts COMMENT 'カート'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
