<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_id')->nullable()->after('remember_token');
            $table->unsignedBigInteger('cash_register_id')->nullable()->after('branch_id');

            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('set null');
            $table->foreign('cash_register_id')->references('id')->on('cash_registers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropForeign(['cash_register_id']);
            $table->dropColumn('branch_id');
            $table->dropColumn('cash_register_id');
        });
    }
};
