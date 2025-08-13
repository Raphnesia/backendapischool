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
        Schema::create('marquee_texts', function (Blueprint $table) {
            $table->id();
            $table->text('text');
            $table->integer('order_index')->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('color')->default('#ffffff');
            $table->string('speed')->default('normal'); // slow, normal, fast
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marquee_texts');
    }
}; 