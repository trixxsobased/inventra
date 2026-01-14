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
        Schema::table('equipment', function (Blueprint $table) {
            // Valuasi aset
            $table->decimal('price', 15, 2)->default(0)->after('condition')
                ->comment('Harga perolehan aset (Rupiah)');
            
            // Manajemen lifecycle
            $table->year('purchase_year')->nullable()->after('price')
                ->comment('Tahun pembelian aset');
            
            // Tracking vendor
            $table->string('vendor', 255)->nullable()->after('purchase_year')
                ->comment('Nama supplier/vendor');
            
            // Tambah index untuk query laporan
            $table->index('price');
            $table->index('purchase_year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equipment', function (Blueprint $table) {
            $table->dropIndex(['price']);
            $table->dropIndex(['purchase_year']);
            $table->dropColumn(['price', 'purchase_year', 'vendor']);
        });
    }
};
