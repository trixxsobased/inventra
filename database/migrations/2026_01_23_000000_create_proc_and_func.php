<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create Function: calculate_late_fine
        // Menghitung denda berdasarkan keterlambatan
        DB::unprepared('
            DROP FUNCTION IF EXISTS calculate_late_fine;
            
            CREATE FUNCTION calculate_late_fine(
                p_planned_date DATE,
                p_return_date DATE,
                p_fine_per_day DECIMAL(10,2)
            ) 
            RETURNS DECIMAL(10,2)
            DETERMINISTIC
            BEGIN
                DECLARE v_days_late INT;
                DECLARE v_fine_amount DECIMAL(10,2);
                
                IF p_return_date <= p_planned_date THEN
                    SET v_fine_amount = 0;
                ELSE
                    SET v_days_late = DATEDIFF(p_return_date, p_planned_date);
                    SET v_fine_amount = v_days_late * p_fine_per_day;
                END IF;
                
                RETURN v_fine_amount;
            END
        ');

        // 2. Create Stored Procedure: sp_return_equipment
        // Menangani proses pengembalian (Alternatif dari Eloquent untuk syarat ujian)
        DB::unprepared('
            DROP PROCEDURE IF EXISTS sp_return_equipment;
            
            CREATE PROCEDURE sp_return_equipment(
                IN p_borrowing_id BIGINT,
                IN p_return_date DATE,
                IN p_condition VARCHAR(50)
            )
            BEGIN
                -- Update data peminjaman
                UPDATE borrowings
                SET 
                    actual_return_date = p_return_date,
                    status = "returned",
                    updated_at = NOW()
                WHERE id = p_borrowing_id;
                
                -- Update history kondisi jika rusak (Opsional, logika sederhana)
                -- (Trigger increase_stock_on_return akan otomatis jalan setelah UPDATE ini)
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP FUNCTION IF EXISTS calculate_late_fine');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_return_equipment');
    }
};
