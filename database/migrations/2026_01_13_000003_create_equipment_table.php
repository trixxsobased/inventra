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
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->foreignId('category_id')->constrained('categories')->onDelete('restrict');
            $table->text('description')->nullable();
            $table->integer('stock')->default(0);
            $table->string('location')->nullable();
            $table->enum('condition', ['baik', 'rusak ringan', 'rusak berat', 'maintenance'])->default('baik');
            $table->string('image')->nullable();
            $table->timestamps();

            // Index untuk performa query
            $table->index('code');
            $table->index('category_id');
            $table->index('stock');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment');
    }
};
