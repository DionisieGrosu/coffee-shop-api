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
        Schema::create('order_coffees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->references('id')->cascadeOnDelete();
            $table->foreignId('coffee_id')->constrained('coffees')->references('id')->cascadeOnDelete();
            $table->decimal('price');
            $table->integer('qt');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_coffees');
    }
};
