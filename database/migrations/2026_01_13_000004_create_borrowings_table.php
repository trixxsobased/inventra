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
        Schema::create('borrowings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('equipment_id')->constrained('equipment')->onDelete('restrict');
            $table->date('borrow_date');
            $table->date('planned_return_date');
            $table->date('actual_return_date')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'borrowed', 'returned'])->default('pending');
            $table->text('purpose')->nullable(); // Tujuan peminjaman
            $table->text('notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            // Index untuk performa query
            $table->index('user_id');
            $table->index('equipment_id');
            $table->index('status');
            $table->index('borrow_date');
            $table->index('actual_return_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrowings');
    }
};
