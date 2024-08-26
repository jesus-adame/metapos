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
            $table->decimal('total_sales', 10, 2);
            $table->decimal('total_purchases', 10, 2);
            $table->decimal('sales_balance', 10, 2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cash_cuts', function (Blueprint $table) {
            //
        });
    }
};
