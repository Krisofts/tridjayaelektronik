<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DailyDealerTargetService
{
    public function get(): array
    {
        $cacheKey = 'dealer:daily-target:' . now()->format('Y-m-d');

        return Cache::store('redis')->remember(
            $cacheKey,
            300,
            fn () => $this->fetch()
        );
    }

    private function fetch(): array
    {
        $sql = "
            DECLARE @today DATE = CAST(GETDATE() AS DATE);
            DECLARE @tomorrow DATE = DATEADD(DAY, 1, @today);

            SELECT
                md.Kode AS KodeDealer,
                md.Nama AS NamaDealer,

                mc.Kode AS KodeCabang,
                mc.Nama AS NamaCabang,

                COUNT(DISTINCT h.NoTransaksi) AS TotalTransaksi,

                SUM(ISNULL(d.Jumlah,0)) AS TotalUnit,

                SUM(ISNULL(d.Jumlah,0) * ISNULL(d.Harga,0)) AS TotalHarga,

                SUM(ISNULL(d.Diskon,0)) AS TotalDiskon,

                SUM(
                    (ISNULL(d.Jumlah,0) * ISNULL(d.Harga,0)) - ISNULL(d.Diskon,0)
                ) AS TotalSales,

                ISNULL(t.TargetSales, 0) AS DailyTarget

            FROM mDealer md

            LEFT JOIN tHeaderPenjualanBarang h
                ON h.KodeDealer = md.Kode
                AND h.Tanggal >= @today
                AND h.Tanggal < @tomorrow

            LEFT JOIN tDetailPenjualanBarang d
                ON d.NoTransaksi = h.NoTransaksi
                AND d.Hapus = '0'

            LEFT JOIN mCabang mc
                ON h.KodeCabang = mc.Kode

            LEFT JOIN tDealerTarget t
                ON t.KodeDealer = md.Kode
                AND t.TipeTarget = 'DAILY'
                AND t.Tanggal = @today

            GROUP BY
                md.Kode,
                md.Nama,
                mc.Kode,
                mc.Nama,
                t.TargetSales

            ORDER BY TotalSales DESC
        ";

        $rows = DB::connection('sqlsrv')->select($sql);

        return collect($rows)->map(function ($row) {

            $achieve = (float) ($row->TotalSales ?? 0);
            $target  = (float) ($row->DailyTarget ?? 0);

            return [
                'dealer_code' => $row->KodeDealer,
                'dealer_name' => $row->NamaDealer,

                'branch_code' => $row->KodeCabang,
                'branch_name' => $row->NamaCabang,

                'transactions' => (int) ($row->TotalTransaksi ?? 0),
                'units' => (float) ($row->TotalUnit ?? 0),

                'gross_sales' => (float) ($row->TotalHarga ?? 0),
                'discount' => (float) ($row->TotalDiskon ?? 0),

                // ACHIEVEMENT REAL
                'achieve' => $achieve,

                // TARGET
                'daily_target' => $target,

                // PERCENT
                'achievement_percent' => $target > 0
                    ? round(($achieve / $target) * 100, 2)
                    : 0,

                // STATUS DASHBOARD
                'status' => $this->getStatus($target, $achieve),
            ];
        })->toArray();
    }

    private function getStatus(float $target, float $achieve): string
    {
        if ($target <= 0) {
            return 'NO_TARGET';
        }

        $percent = ($achieve / $target) * 100;

        return match (true) {
            $percent >= 100 => 'ACHIEVED',
            $percent >= 80  => 'GOOD',
            $percent >= 50  => 'WARNING',
            default         => 'LOW',
        };
    }
}