<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class SalesReportService
{
    /**
     * =========================
     * DAILY & MONTHLY SUMMARY
     * =========================
     */

    public function getTodaySalesReport(): ?object
    {
        return $this->getSalesByDateRange('today');
    }

    public function getYesterdaySalesReport(): ?object
    {
        return $this->getSalesByDateRange('yesterday');
    }

    public function getMonthSalesReport(): ?object
    {
        return $this->getSalesByDateRange('month');
    }

    /**
     * =========================
     * 🏢 MONTHLY SALES BY DEALER
     * =========================
     */
    public function getMonthlySalesByDealer(): array
    {
        try {

            $sql = "
                SELECT 
                    {$this->dealerCase()} AS NamaDealer,

                    COUNT(DISTINCT h.NoTransaksi) AS TotalTransaksi,

                    COALESCE(SUM(d.Jumlah),0) AS TotalQty,

                    COALESCE(SUM(d.Harga * d.Jumlah),0) AS TotalKotor,

                    COALESCE(SUM(d.Diskon * d.Jumlah),0) AS TotalDiskon,

                    COALESCE(SUM((d.Harga - d.Diskon) * d.Jumlah),0) AS TotalPenjualan

                FROM tHeaderPenjualanBarang h

                INNER JOIN tDetailPenjualanBarang d
                    ON h.NoTransaksi = d.NoTransaksi
                    AND d.Hapus = '0'

                WHERE 
                    h.Tanggal >= DATEFROMPARTS(YEAR(GETDATE()), MONTH(GETDATE()), 1)
                    AND h.Tanggal < DATEADD(MONTH, 1, DATEFROMPARTS(YEAR(GETDATE()), MONTH(GETDATE()), 1))

                GROUP BY 
                    h.KodeDealer

                ORDER BY 
                    TotalPenjualan DESC
            ";

            return DB::connection('sqlsrv')->select($sql);

        } catch (\Exception $e) {
            logger()->error('Monthly Dealer Report Error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * =========================
     * 🏆 TOP 10 SALES EMPLOYEE
     * =========================
     */
    public function getTopSalesEmployees(): array
    {
        try {

            $sql = "
                SELECT TOP 10
                    p.kode AS KodePegawai,
                    p.nama AS NamaPegawai,

                    COUNT(DISTINCT h.NoTransaksi) AS TotalTransaksi,

                    COALESCE(SUM(d.Jumlah),0) AS TotalQty,

                    COALESCE(SUM(d.Harga * d.Jumlah),0) AS TotalKotor,

                    COALESCE(SUM(d.Diskon * d.Jumlah),0) AS TotalDiskon,

                    COALESCE(SUM((d.Harga - d.Diskon) * d.Jumlah),0) AS TotalPenjualan

                FROM tHeaderPenjualanBarang h

                INNER JOIN tDetailPenjualanBarang d
                    ON h.NoTransaksi = d.NoTransaksi
                    AND d.Hapus = '0'

                INNER JOIN mPegawai p
                    ON h.KodeSales = p.kode

                WHERE 
                    h.Tanggal >= DATEFROMPARTS(YEAR(GETDATE()), MONTH(GETDATE()), 1)
                    AND h.Tanggal < DATEADD(MONTH, 1, DATEFROMPARTS(YEAR(GETDATE()), MONTH(GETDATE()), 1))

                GROUP BY 
                    p.kode,
                    p.nama

                ORDER BY 
                    TotalPenjualan DESC
            ";

            return DB::connection('sqlsrv')->select($sql);

        } catch (\Exception $e) {
            logger()->error('Top Sales Employee Error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * =========================
     * CORE SUMMARY QUERY
     * =========================
     */
    private function getSalesByDateRange(string $type): ?object
    {
        try {

            $dateCondition = $this->getDateCondition($type);

            $sql = "
                SELECT 
                    COUNT(DISTINCT h.NoTransaksi) AS TotalTransaksi,

                    COALESCE(SUM(d.Jumlah),0) AS TotalUnit,

                    COALESCE(SUM(d.Jumlah * d.Harga),0) AS TotalHarga,

                    COALESCE(SUM(d.Diskon),0) AS TotalDiskon,

                    COALESCE(SUM(d.Jumlah * d.Harga - d.Diskon),0) AS TotalSetelahDiskon

                FROM tDetailPenjualanBarang d

                JOIN tHeaderPenjualanBarang h
                    ON d.NoTransaksi = h.NoTransaksi

                WHERE 
                    d.Hapus = '0'
                    AND $dateCondition
            ";

            $result = DB::connection('sqlsrv')->select($sql);

            return $result[0] ?? (object) [
                'TotalTransaksi' => 0,
                'TotalUnit' => 0,
                'TotalHarga' => 0,
                'TotalDiskon' => 0,
                'TotalSetelahDiskon' => 0,
            ];

        } catch (\Exception $e) {
            logger()->error('SalesReportService Error: ' . $e->getMessage());

            return (object) [
                'TotalTransaksi' => 0,
                'TotalUnit' => 0,
                'TotalHarga' => 0,
                'TotalDiskon' => 0,
                'TotalSetelahDiskon' => 0,
            ];
        }
    }

    /**
     * =========================
     * DEALER MAPPING
     * =========================
     */
    private function dealerCase(): string
    {
        return "
            CASE h.KodeDealer
                WHEN 'D-01' THEN 'Pagaden'
                WHEN 'D-02' THEN 'Haurgeulis'
                WHEN 'D-03' THEN 'Soklat'
                WHEN 'D-04' THEN 'Patokbeusi'
                WHEN 'D-05' THEN 'Pamanukan'
                WHEN 'D-06' THEN 'Samrat'
                WHEN 'D-07' THEN 'Bahu'
                WHEN 'D-08' THEN 'Purwadadi'
                WHEN 'D-09' THEN 'Cimalaka'
                WHEN 'D-10' THEN 'Cikampek'
                WHEN 'D-11' THEN 'Pabuaran'
                WHEN 'D-12' THEN 'Cibaduyut'
                WHEN 'D-13' THEN 'Cilacap'
                ELSE h.KodeDealer
            END
        ";
    }

    /**
     * =========================
     * DATE FILTERS
     * =========================
     */
    private function getDateCondition(string $type): string
    {
        return match ($type) {

            'today' => "
                h.Tanggal >= CONVERT(DATE, GETDATE())
                AND h.Tanggal < DATEADD(DAY, 1, CONVERT(DATE, GETDATE()))
            ",

            'yesterday' => "
                h.Tanggal >= DATEADD(DAY, -1, CONVERT(DATE, GETDATE()))
                AND h.Tanggal < CONVERT(DATE, GETDATE())
            ",

            'month' => "
                h.Tanggal >= DATEFROMPARTS(YEAR(GETDATE()), MONTH(GETDATE()), 1)
                AND h.Tanggal < DATEADD(MONTH, 1, DATEFROMPARTS(YEAR(GETDATE()), MONTH(GETDATE()), 1))
            ",

            default => throw new \Exception("Invalid date type: {$type}"),
        };
    }
}