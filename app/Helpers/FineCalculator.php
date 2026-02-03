<?php

namespace App\Helpers;

use Carbon\Carbon;

class FineCalculator
{
    public static function calculate(string $plannedReturnDate, string $actualReturnDate, float $ratePerDay = 5000): array
    {
        $planned = Carbon::parse($plannedReturnDate);
        $actual = Carbon::parse($actualReturnDate);

        $daysLate = $actual->diffInDays($planned, false);

        if ($daysLate < 0) {
            $daysLate = abs($daysLate);
            $fineAmount = $daysLate * $ratePerDay;
            
            return [
                'is_late' => true,
                'days_late' => $daysLate,
                'rate_per_day' => $ratePerDay,
                'fine_amount' => $fineAmount,
                'message' => "Terlambat {$daysLate} hari. Denda: Rp " . number_format($fineAmount, 0, ',', '.')
            ];
        }

        return [
            'is_late' => false,
            'days_late' => 0,
            'rate_per_day' => $ratePerDay,
            'fine_amount' => 0,
            'message' => 'Pengembalian tepat waktu. Tidak ada denda.'
        ];
    }

    public static function formatRupiah(float $amount): string
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }

    public static function getDetailedInfo(string $plannedReturnDate, ?string $actualReturnDate = null, float $ratePerDay = 5000): array
    {
        $actualDate = $actualReturnDate ?? Carbon::now()->toDateString();
        $result = self::calculate($plannedReturnDate, $actualDate, $ratePerDay);

        return [
            'planned_return_date' => $plannedReturnDate,
            'actual_return_date' => $actualDate,
            'is_late' => $result['is_late'],
            'days_late' => $result['days_late'],
            'rate_per_day' => $ratePerDay,
            'rate_per_day_formatted' => self::formatRupiah($ratePerDay),
            'fine_amount' => $result['fine_amount'],
            'fine_amount_formatted' => self::formatRupiah($result['fine_amount']),
            'message' => $result['message']
        ];
    }
}
