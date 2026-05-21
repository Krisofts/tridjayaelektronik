<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class SalesSummaryService
{
    public function getTodayVsYesterday(): array
    {
        return Cache::store('redis')->remember(
            'sales:tvsy:' . now()->format('Y-m-d-H-i'),
            300,
            function () {

                $rows = $this->getSummaryTodayYesterday();

                $today = $this->format($rows['today'] ?? null);
                $yesterday = $this->format($rows['yesterday'] ?? null);

                return [
                    'today'      => $today,
                    'yesterday'  => $yesterday,
                    'comparison' => $this->buildComparison($today, $yesterday),
                ];
            }
        );
    }

    private function getSummaryTodayYesterday(): array
    {
        $sql = "
            DECLARE @today DATE = CONVERT(DATE, GETDATE());
            DECLARE @tomorrow DATE = DATEADD(DAY, 1, @today);
            DECLARE @yesterday DATE = DATEADD(DAY, -1, @today);

            SELECT
                period,

                COUNT(DISTINCT NoTransaksi) AS TotalTransaksi,

                SUM(Jumlah) AS TotalUnit,

                SUM(Jumlah * Harga) AS TotalHarga,

                SUM(Diskon) AS TotalDiskon,

                SUM((Jumlah * Harga) - Diskon) AS TotalSetelahDiskon

            FROM (
                SELECT
                    h.NoTransaksi,

                    CASE
                        WHEN h.Tanggal >= @today THEN 'today'
                        ELSE 'yesterday'
                    END AS period,

                    ISNULL(d.Jumlah, 0) AS Jumlah,
                    ISNULL(d.Harga, 0) AS Harga,
                    ISNULL(d.Diskon, 0) AS Diskon

                FROM tHeaderPenjualanBarang h

                LEFT JOIN tDetailPenjualanBarang d
                    ON d.NoTransaksi = h.NoTransaksi
                    AND d.Hapus = '0'

                WHERE h.Tanggal >= @yesterday
                  AND h.Tanggal < @tomorrow
            ) x

            GROUP BY period
        ";

        $result = DB::connection('sqlsrv')->select($sql);

        return $this->normalizeRows($result);
    }

    private function normalizeRows(array $rows): array
    {
        $data = [];

        foreach ($rows as $row) {
            $data[$row->period] = [
                'TotalTransaksi'     => (int) ($row->TotalTransaksi ?? 0),
                'TotalUnit'          => (float) ($row->TotalUnit ?? 0),
                'TotalHarga'         => (float) ($row->TotalHarga ?? 0),
                'TotalDiskon'        => (float) ($row->TotalDiskon ?? 0),
                'TotalSetelahDiskon' => (float) ($row->TotalSetelahDiskon ?? 0),
            ];
        }

        return $data;
    }

    private function format(?array $row): array
    {
        $row ??= [
            'TotalTransaksi' => 0,
            'TotalUnit' => 0,
            'TotalHarga' => 0,
            'TotalDiskon' => 0,
            'TotalSetelahDiskon' => 0,
        ];

        $row['TotalAOV'] = $this->aov(
            $row['TotalSetelahDiskon'],
            $row['TotalTransaksi']
        );

        return $row;
    }

    private function buildComparison(array $today, array $yesterday): array
    {
        $metrics = [
            'transaksi' => 'TotalTransaksi',
            'unit'      => 'TotalUnit',
            'sales'     => 'TotalSetelahDiskon',
            'aov'       => 'TotalAOV',
        ];

        $result = [];

        foreach ($metrics as $key => $field) {

            $current  = (float) ($today[$field] ?? 0);
            $previous = (float) ($yesterday[$field] ?? 0);

            $result[$key . '_diff'] =
                round($current - $previous, 2);

            $result[$key . '_growth'] =
                $this->growth($current, $previous);
        }

        return $result;
    }

    private function growth(float $current, float $previous): float
    {
        if ($previous <= 0) {
            return $current > 0 ? 100.0 : 0.0;
        }

        return round(
            (($current - $previous) / $previous) * 100,
            2
        );
    }

    private function aov(float $sales, float $transactions): float
    {
        if ($transactions <= 0) {
            return 0.0;
        }

        return round($sales / $transactions, 2);
    }
}