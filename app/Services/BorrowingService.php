<?php

namespace App\Services;

use App\Models\Borrowing;
use App\Models\Equipment;
use App\Models\User;
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

    // Proses pengembalian alat + auto tambah stock via trigger
    public function processReturn(int $borrowingId, string $returnDate, int $verifiedBy): array
    {
        try {
            DB::beginTransaction();

            $borrowing = Borrowing::with('equipment')->findOrFail($borrowingId);
            
            if ($borrowing->status !== 'borrowed') {
                throw new Exception('Peminjaman ini tidak dapat dikembalikan. Status: ' . $borrowing->status);
            }

            // Update status jadi 'returned' - trigger DB auto nambah stock lagi
            $borrowing->update([
                'status' => 'returned',
                'actual_return_date' => $returnDate,
            ]);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Pengembalian berhasil diproses. Stok telah ditambahkan.',
                'borrowing' => $borrowing->fresh(),
                'current_stock' => $borrowing->equipment->fresh()->stock
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
