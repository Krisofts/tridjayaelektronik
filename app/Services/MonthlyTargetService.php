<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class MonthlyTargetService
{
    public function getMonthlyTargetSummary(): array
    {
        return Cache::store('redis')->remember(
            'sales:monthly-target:' . now()->format('Y-m-d-H-i'),
            300,
            function () {

                $row = $this->getMonthlySummary();

                return $this->format($row);
            }
        );
    }

    private function getMonthlySummary(): ?object
    {
        $sql = "
            DECLARE @startMonth DATE =
                DATEFROMPARTS(YEAR(GETDATE()), MONTH(GETDATE()), 1);

            DECLARE @nextMonth DATE =
                DATEADD(MONTH, 1, @startMonth);

            SELECT

                -- TARGET
                CAST(10000000000 AS BIGINT) AS MonthlyTarget,

                -- ACTUAL SALES
                ISNULL(SUM(
                    (d.Jumlah * d.Harga) - d.Diskon
                ), 0) AS MonthlySales,

                -- TOTAL TRANSACTION
                COUNT(DISTINCT h.NoTransaksi) AS TotalTransaction

            FROM tHeaderPenjualanBarang h

            LEFT JOIN tDetailPenjualanBarang d
                ON d.NoTransaksi = h.NoTransaksi
                AND d.Hapus = '0'

            WHERE h.Tanggal >= @startMonth
              AND h.Tanggal < @nextMonth
        ";

        return DB::connection('sqlsrv')->selectOne($sql);
    }

    private function format(?object $row): array
    {
        $target = (float) ($row->MonthlyTarget ?? 0);
        $sales  = (float) ($row->MonthlySales ?? 0);

        $daysInMonth = now()->daysInMonth;
        $currentDay  = now()->day;
        $remainingDays = max($daysInMonth - $currentDay, 0);

        $achievement = $this->percentage($sales, $target);

        $remainingTarget = max($target - $sales, 0);

        $dailyRunRate = $currentDay > 0
            ? $sales / $currentDay
            : 0;

        $projectedClosing =
            $dailyRunRate * $daysInMonth;

        return [

            'target' => round($target, 2),

            'sales' => round($sales, 2),

            'achievement' => round($achievement, 2),

            'remaining_target' => round($remainingTarget, 2),

            'daily_run_rate' => round($dailyRunRate, 2),

            'projected_closing' => round($projectedClosing, 2),

            'days_in_month' => $daysInMonth,

            'current_day' => $currentDay,

            'remaining_days' => $remainingDays,

            'transactions' => (int) ($row->TotalTransaction ?? 0),
        ];
    }

    private function percentage(float $value, float $target): float
    {
        if ($target <= 0) {
            return 0;
        }

        return ($value / $target) * 100;
    }
}