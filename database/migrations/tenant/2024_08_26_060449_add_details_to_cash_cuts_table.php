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
            $table->decimal('cash_amount', 10, 2);
            $table->decimal('card_amount', 10, 2);
            $table->decimal('transfer_amount', 10, 2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cash_cuts', function (Blueprint $table) {
            $table->dropColumn('cash_amount');
            $table->dropColumn('card_amount');
            $table->dropColumn('transfer_amount');
        });
    }
};
