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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->nullable();
            $table->string('name');
            $table->string('sku')->nullable();
            $table->string('description')->nullable();
            $table->decimal('cost', 8, 2);
            $table->decimal('price', 8, 2);
            $table->decimal('wholesale_price', 10, 2)->default(0);
            $table->string('unit_type')->nullable();
            $table->decimal('tax', 8, 2);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
