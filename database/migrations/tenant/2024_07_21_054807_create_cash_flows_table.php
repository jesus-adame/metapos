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
        Schema::create('cash_flows', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cash_register_id')->nullable();
            $table->enum('type', ['entry', 'exit']);
            $table->enum('method', ['cash', 'card', 'transfer'])->default('cash');
            $table->decimal('amount', 10, 2);
            $table->string('description')->nullable();
            $table->dateTime('date');
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
        Schema::dropIfExists('cash_flows');
    }
};
