<?php


namespace App\Services;

use App\Models\Borrowing;
use App\Models\Equipment;
use App\Models\User;
use App\Models\DamagedEquipment;
use Illuminate\Support\Facades\DB;
use Exception;

class BorrowingService
{
    // Verifikasi peminjaman (approve/reject) + auto update stock via trigger
    public function verifyBorrowing(int $borrowingId, int $verifiedBy, bool $approve, ?string $rejectionReason = null): array
    {
        try {
            DB::beginTransaction();

            $borrowing = Borrowing::with('equipment')->findOrFail($borrowingId);
            
            if ($borrowing->status !== 'pending') {
                throw new \Exception('Peminjaman sudah diverifikasi sebelumnya');
            }

            if ($approve) {
                // Cek stock sebelum approve
                if ($borrowing->equipment->stock < 1) {
                    DB::rollBack();
                    return [
                        'success' => false,
                        'message' => 'Stok tidak mencukupi. Peminjaman tidak dapat diapprove.',
                        'stock' => $borrowing->equipment->stock
                    ];
                }

                // Ubah status ke 'borrowed' - trigger DB bakal auto kurangin stock
                $borrowing->update([
                    'status' => 'borrowed',
                    'verified_by' => $verifiedBy,
                    'verified_at' => now(),
                    'borrow_date' => now()->toDateString(),
                ]);

                DB::commit();

                return [
                    'success' => true,
                    'message' => 'Peminjaman berhasil diapprove. Stok telah dikurangi.',
                    'borrowing' => $borrowing->fresh(),
                    'remaining_stock' => $borrowing->equipment->fresh()->stock
                ];

            } else {
                $borrowing->update([
                    'status' => 'rejected',
                    'verified_by' => $verifiedBy,
                    'verified_at' => now(),
                    'rejection_reason' => $rejectionReason,
                ]);

                DB::commit();

                return [
                    'success' => true,
                    'message' => 'Peminjaman berhasil ditolak.',
                    'borrowing' => $borrowing->fresh()
                ];
            }

        } catch (Exception $e) {
            DB::rollBack();
            
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    // Proses pengembalian alat - handle kondisi barang rusak
    public function processReturn(int $borrowingId, string $returnDate, int $verifiedBy, ?string $returnCondition = null): array
    {
        try {
            DB::beginTransaction();

            $borrowing = Borrowing::with('equipment')->findOrFail($borrowingId);
            
            if ($borrowing->status !== 'borrowed') {
                throw new Exception('Peminjaman ini tidak dapat dikembalikan. Status: ' . $borrowing->status);
            }

            $isSeverelyDamaged = $returnCondition === 'rusak berat';
            
            // Update borrowing dengan return condition
            $borrowing->update([
                'status' => 'returned',
                'actual_return_date' => $returnDate,
                'return_condition' => $returnCondition,
            ]);

            $message = 'Pengembalian berhasil diproses.';

            // Handle barang rusak berat - JANGAN restore stock
            if ($isSeverelyDamaged) {
                // Create record di damaged_equipment
                DamagedEquipment::create([
                    'equipment_id' => $borrowing->equipment_id,
                    'borrowing_id' => $borrowing->id,
                    'reported_by' => $verifiedBy,
                    'reported_at' => now(),
                    'damage_description' => 'Dikembalikan dalam kondisi rusak berat dari peminjaman #' . $borrowing->id,
                    'resolution_status' => 'pending',
                ]);

                // Update kondisi equipment jadi rusak berat
                $borrowing->equipment->update([
                    'condition' => 'rusak berat'
                ]);

                $message = 'Pengembalian berhasil. Barang rusak berat telah dicatat dan tidak dikembalikan ke stok.';
            } else {
                // Untuk kondisi baik atau rusak ringan, trigger DB akan auto restore stock
                if ($returnCondition === 'rusak ringan') {
                    $message = 'Pengembalian berhasil. Stok telah ditambahkan (kondisi: rusak ringan).';
                } else {
                    $message = 'Pengembalian berhasil. Stok telah ditambahkan.';
                }
            }

            DB::commit();

            return [
                'success' => true,
                'message' => $message,
                'borrowing' => $borrowing->fresh(),
                'current_stock' => $borrowing->equipment->fresh()->stock,
                'is_damaged' => $isSeverelyDamaged,
            ];

        } catch (Exception $e) {
            DB::rollBack();
            
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    public function createBorrowingRequest(array $data): Borrowing
    {
        return Borrowing::create([
            'user_id' => $data['user_id'],
            'equipment_id' => $data['equipment_id'],
            'planned_return_date' => $data['planned_return_date'],
            'purpose' => $data['purpose'] ?? null,
            'notes' => $data['notes'] ?? null,
            'status' => 'pending',
        ]);
    }
}
