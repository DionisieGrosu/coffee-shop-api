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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coffee_id')->constrained('coffees')->references('id')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->references('id')->cascadeOnDelete();
            $table->foreignId('size_id')->constrained('sizes')->references('id')->cascadeOnDelete();
            $table->integer('qt');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
