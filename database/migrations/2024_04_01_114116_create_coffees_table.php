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
        Schema::create('coffees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('sorder')->default(1);
            $table->string('img')->nullable();
            $table->string('topics')->nullable();
            $table->tinyInteger('is_active')->default(1);
            $table->foreignId('category_id')->nullable()->constrained('categories')->references('id')->nullOnDelete();
            // $table->foreignId('topic_id')->nullable()->constrained('topics')->references('id')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coffees');
    }
};
