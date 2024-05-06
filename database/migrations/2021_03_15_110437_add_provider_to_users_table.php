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
        Schema::table('users', static function (Blueprint $table) {
            // カラム追加
            $table->string('provider_id')->comment('プロバイダID')->nullable()->after('id');
            $table->string('provider_name')->comment('プロバイダ名')->nullable()->after('provider_id');
            // null許容に変更
            $table->string('password')->nullable()->change();

            // 複合ユニークキー追加
            $table->unique(['provider_id', 'provider_name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('users', static function (Blueprint $table) {
            //
            // 複合ユニークキー削除
            $table->dropUnique(['provider_id', 'provider_name']);
            // カラム削除
            $table->dropColumn('provider_id');
            $table->dropColumn('provider_name');
            // NULLを許容しない
            $table->string('password')->nullable(false)->change();
        });
    }
};
