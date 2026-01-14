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
        // Trigger 1: Auto-decrement stok ketika status borrowing berubah menjadi 'borrowed'
        DB::unprepared('
            CREATE TRIGGER decrease_stock_on_borrow
            AFTER UPDATE ON borrowings
            FOR EACH ROW
            BEGIN
                IF NEW.status = "borrowed" AND OLD.status != "borrowed" THEN
                    UPDATE equipment 
                    SET stock = stock - 1 
                    WHERE id = NEW.equipment_id AND stock > 0;
                    
                    INSERT INTO equipment_logs (equipment_id, action, borrowing_id, stock_before, stock_after, quantity, performed_by, created_at)
                    SELECT 
                        NEW.equipment_id,
                        "borrow",
                        NEW.id,
                        (SELECT stock + 1 FROM equipment WHERE id = NEW.equipment_id),
                        (SELECT stock FROM equipment WHERE id = NEW.equipment_id),
                        1,
                        NEW.verified_by,
                        NOW();
                END IF;
            END
        ');

        // Trigger 2: Auto-increment stok ketika actual_return_date diisi (status menjadi 'returned')
        DB::unprepared('
            CREATE TRIGGER increase_stock_on_return
            AFTER UPDATE ON borrowings
            FOR EACH ROW
            BEGIN
                IF NEW.status = "returned" AND OLD.status != "returned" THEN
                    UPDATE equipment 
                    SET stock = stock + 1 
                    WHERE id = NEW.equipment_id;
                    
                    INSERT INTO equipment_logs (equipment_id, action, borrowing_id, stock_before, stock_after, quantity, performed_by, created_at)
                    SELECT 
                        NEW.equipment_id,
                        "return",
                        NEW.id,
                        (SELECT stock - 1 FROM equipment WHERE id = NEW.equipment_id),
                        (SELECT stock FROM equipment WHERE id = NEW.equipment_id),
                        1,
                        NEW.verified_by,
                        NOW();
                END IF;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS decrease_stock_on_borrow');
        DB::unprepared('DROP TRIGGER IF EXISTS increase_stock_on_return');
    }
};
