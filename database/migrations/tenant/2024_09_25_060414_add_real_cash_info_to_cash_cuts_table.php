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
        Schema::table('cash_cuts', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->index();
            $table->decimal('real_cash_amount', 10, 2);
            $table->decimal('real_card_amount', 10, 2);
            $table->decimal('real_transfer_amount', 10, 2);
            $table->decimal('real_total', 10, 2);
            $table->decimal('real_final_balance', 10, 2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cash_cuts', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->dropColumn('real_cash_amount');
            $table->dropColumn('real_card_amount');
            $table->dropColumn('real_transfer_amount');
            $table->dropColumn('real_total');
            $table->dropColumn('real_final_balance');
        });
    }
};
