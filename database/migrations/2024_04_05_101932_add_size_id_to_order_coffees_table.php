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
        Schema::table('order_coffees', function (Blueprint $table) {
            $table->foreignId('size_id')->constrained('sizes')->references('id')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_coffees', function (Blueprint $table) {
            $table->dropColumn('size_id');
        });
    }
};
