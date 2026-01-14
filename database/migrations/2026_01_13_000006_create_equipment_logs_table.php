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
        Schema::create('equipment_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained('equipment')->onDelete('cascade');
            $table->enum('action', ['borrow', 'return', 'add_stock', 'reduce_stock', 'maintenance']);
            $table->foreignId('borrowing_id')->nullable()->constrained('borrowings')->onDelete('set null');
            $table->integer('stock_before');
            $table->integer('stock_after');
            $table->integer('quantity')->default(1); // Jumlah perubahan
            $table->foreignId('performed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamp('created_at');

            // Index untuk audit trail
            $table->index('equipment_id');
            $table->index('action');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_logs');
    }
};
