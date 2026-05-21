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
     * 📊 YEARLY SALES BY MONTH
     * =========================
     */
    public function getYearlySalesByMonth(): array
    {
        try {

            $sql = "
                SELECT 
                    MONTH(h.Tanggal) AS NoBulan,

                    DATENAME(MONTH, h.Tanggal) AS NamaBulan,

                    COUNT(DISTINCT h.NoTransaksi) AS TotalTransaksi,

                    COALESCE(SUM(d.Jumlah),0) AS TotalUnit,

                    COALESCE(SUM(d.Jumlah * d.Harga),0) AS TotalHarga,

                    COALESCE(SUM(d.Diskon),0) AS TotalDiskon,

                    COALESCE(SUM(d.Jumlah * d.Harga - d.Diskon),0) AS TotalSetelahDiskon

                FROM tDetailPenjualanBarang d

                INNER JOIN tHeaderPenjualanBarang h
                    ON d.NoTransaksi = h.NoTransaksi

                WHERE 
                    d.Hapus = '0'
                    AND YEAR(h.Tanggal) = YEAR(GETDATE())

                GROUP BY 
                    MONTH(h.Tanggal),
                    DATENAME(MONTH, h.Tanggal)

                ORDER BY 
                    MONTH(h.Tanggal)
            ";

            return DB::connection('sqlsrv')->select($sql);

        } catch (\Exception $e) {

            logger()->error('Yearly Sales By Month Error: ' . $e->getMessage());

            return [];
        }
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

            if (empty($result)) {
                return (object) [
                    'TotalTransaksi' => 0,
                    'TotalUnit' => 0,
                    'TotalHarga' => 0,
                    'TotalDiskon' => 0,
                    'TotalSetelahDiskon' => 0,
                ];
            }

            return $result[0];

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
 * 📊 SALES STATISTICS
 * =========================
 *
 * daily   = 30 hari terakhir
 * weekly  = 12 minggu terakhir
 * monthly = 12 bulan terakhir
 */
/**
 * =========================
 * 📊 SALES STATISTICS
 * =========================
 *
 * daily   = 30 hari terakhir
 * weekly  = 12 minggu terakhir
 * monthly = 12 bulan terakhir
 */
public function getSalesStatistics(
    string $range = 'monthly'
): array {

    try {

        switch ($range) {

            /**
             * =========================
             * DAILY
             * 30 HARI TERAKHIR
             * =========================
             */
            case 'daily':

                $sql = "

                    WITH Dates AS (

                        SELECT
                            CAST(
                                DATEADD(DAY, -29, GETDATE())
                                AS DATE
                            ) AS SalesDate

                        UNION ALL

                        SELECT
                            DATEADD(DAY, 1, SalesDate)

                        FROM Dates

                        WHERE SalesDate < CAST(GETDATE() AS DATE)
                    )

                    SELECT

                        CONVERT(VARCHAR, d.SalesDate, 23)
                            AS Tanggal,

                        DAY(d.SalesDate)
                            AS Hari,

                        MONTH(d.SalesDate)
                            AS Bulan,

                        YEAR(d.SalesDate)
                            AS Tahun,

                        COUNT(DISTINCT h.NoTransaksi)
                            AS TotalTransaksi,

                        COALESCE(
                            SUM(dt.Jumlah),
                            0
                        ) AS TotalUnit,

                        COALESCE(
                            SUM(
                                (dt.Harga - dt.Diskon)
                                * dt.Jumlah
                            ),
                            0
                        ) AS TotalPenjualan

                    FROM Dates d

                    LEFT JOIN tHeaderPenjualanBarang h
                        ON CAST(h.Tanggal AS DATE)
                        = d.SalesDate

                    LEFT JOIN tDetailPenjualanBarang dt
                        ON h.NoTransaksi = dt.NoTransaksi
                        AND dt.Hapus = '0'

                    GROUP BY
                        d.SalesDate

                    ORDER BY
                        d.SalesDate

                    OPTION (MAXRECURSION 30)
                ";

                break;

            /**
             * =========================
             * WEEKLY
             * 12 MINGGU TERAKHIR
             * =========================
             */
            case 'weekly':

                $sql = "

                    WITH Weeks AS (

                        SELECT
                            DATEADD(
                                WEEK,
                                -11,
                                CAST(GETDATE() AS DATE)
                            ) AS WeekDate

                        UNION ALL

                        SELECT
                            DATEADD(WEEK, 1, WeekDate)

                        FROM Weeks

                        WHERE WeekDate < GETDATE()
                    )

                    SELECT

                        DATEPART(YEAR, w.WeekDate)
                            AS Tahun,

                        DATEPART(ISO_WEEK, w.WeekDate)
                            AS Minggu,

                        CONCAT(
                            'Week ',
                            DATEPART(
                                ISO_WEEK,
                                w.WeekDate
                            )
                        ) AS Label,

                        COUNT(DISTINCT h.NoTransaksi)
                            AS TotalTransaksi,

                        COALESCE(
                            SUM(dt.Jumlah),
                            0
                        ) AS TotalUnit,

                        COALESCE(
                            SUM(
                                (dt.Harga - dt.Diskon)
                                * dt.Jumlah
                            ),
                            0
                        ) AS TotalPenjualan

                    FROM Weeks w

                    LEFT JOIN tHeaderPenjualanBarang h
                        ON DATEPART(YEAR, h.Tanggal)
                        = DATEPART(YEAR, w.WeekDate)

                        AND DATEPART(ISO_WEEK, h.Tanggal)
                        = DATEPART(ISO_WEEK, w.WeekDate)

                    LEFT JOIN tDetailPenjualanBarang dt
                        ON h.NoTransaksi = dt.NoTransaksi
                        AND dt.Hapus = '0'

                    GROUP BY
                        DATEPART(YEAR, w.WeekDate),
                        DATEPART(ISO_WEEK, w.WeekDate)

                    ORDER BY
                        Tahun,
                        Minggu

                    OPTION (MAXRECURSION 12)
                ";

                break;

            /**
             * =========================
             * MONTHLY
             * 12 BULAN TERAKHIR
             * =========================
             */
            case 'monthly':

                $sql = "

                    WITH Months AS (

                        SELECT
                            DATEFROMPARTS(
                                YEAR(
                                    DATEADD(
                                        MONTH,
                                        -11,
                                        GETDATE()
                                    )
                                ),
                                MONTH(
                                    DATEADD(
                                        MONTH,
                                        -11,
                                        GETDATE()
                                    )
                                ),
                                1
                            ) AS MonthDate

                        UNION ALL

                        SELECT
                            DATEADD(MONTH, 1, MonthDate)

                        FROM Months

                        WHERE MonthDate <
                            DATEFROMPARTS(
                                YEAR(GETDATE()),
                                MONTH(GETDATE()),
                                1
                            )
                    )

                    SELECT

                        YEAR(m.MonthDate)
                            AS Tahun,

                        MONTH(m.MonthDate)
                            AS Bulan,

                        DATENAME(
                            MONTH,
                            m.MonthDate
                        ) AS NamaBulan,

                        COUNT(DISTINCT h.NoTransaksi)
                            AS TotalTransaksi,

                        COALESCE(
                            SUM(dt.Jumlah),
                            0
                        ) AS TotalUnit,

                        COALESCE(
                            SUM(
                                (dt.Harga - dt.Diskon)
                                * dt.Jumlah
                            ),
                            0
                        ) AS TotalPenjualan

                    FROM Months m

                    LEFT JOIN tHeaderPenjualanBarang h
                        ON YEAR(h.Tanggal)
                        = YEAR(m.MonthDate)

                        AND MONTH(h.Tanggal)
                        = MONTH(m.MonthDate)

                    LEFT JOIN tDetailPenjualanBarang dt
                        ON h.NoTransaksi = dt.NoTransaksi
                        AND dt.Hapus = '0'

                    GROUP BY
                        YEAR(m.MonthDate),
                        MONTH(m.MonthDate),
                        DATENAME(MONTH, m.MonthDate)

                    ORDER BY
                        Tahun,
                        Bulan

                    OPTION (MAXRECURSION 12)
                ";

                break;

            default:

                throw new \Exception(
                    "Invalid statistics range: {$range}"
                );
        }

        return DB::connection('sqlsrv')
            ->select($sql);

    } catch (\Exception $e) {

        logger()->error(
            'Sales Statistics Error: ' .
            $e->getMessage()
        );

        return [];
    }
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