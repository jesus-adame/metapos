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
        Schema::create('cash_cuts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cash_register_id')->nullable();
            $table->decimal('total_entries', 10, 2);
            $table->decimal('total_exits', 10, 2);
            $table->decimal('final_balance', 10, 2);
            $table->dateTime('cut_date');
            $table->dateTime('cut_end_date')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('cash_register_id')->references('id')->on('cash_registers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_cuts');
    }
};
