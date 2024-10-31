<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('model_has_roles', function (Blueprint $table) {
            $table->dropPrimary(['role_id', 'model_id', 'model_type', 'team_id']);

            // team_idをNULL許容に変更
            $table->unsignedBigInteger('team_id')->nullable()->change();

            // 新しい主キーを設定（team_idを除外）
            $table->primary(['role_id', 'model_id', 'model_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('model_has_roles', function (Blueprint $table) {
            // 元の状態に戻す
            $table->dropPrimary(['role_id', 'model_id', 'model_type']);

            // team_idを非NULL制約に戻す
            $table->unsignedBigInteger('team_id')->nullable(false)->change();

            // 元の主キーを再設定
            $table->primary(['role_id', 'model_id', 'model_type', 'team_id']);
        });
    }
};
