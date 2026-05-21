<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SalesPerformanceService
{
    public function getSalesPerformance(): array
    {
        return Cache::store('redis')->remember(
            'sales:performance:' . now()->format('Y-m-d-H-i'),
            300,
            fn () => $this->fetch()
        );
    }

    private function fetch(): array
    {
        $sql = "

        DECLARE @TanggalMinus2 DATE =
            CAST(DATEADD(DAY, -1, GETDATE()) AS DATE);

        DECLARE @AwalBulanIni DATE =
            DATEFROMPARTS(
                YEAR(GETDATE()),
                MONTH(GETDATE()),
                1
            );

        DECLARE @AwalBulanLalu DATE =
            DATEADD(MONTH, -1, @AwalBulanIni);

        DECLARE @TanggalPembandingBulanLalu DATE =
            DATEFROMPARTS(
                YEAR(DATEADD(MONTH, -1, GETDATE())),
                MONTH(DATEADD(MONTH, -1, GETDATE())),
                DAY(@TanggalMinus2)
            );

        SELECT TOP 200

            p.nama AS NamaPegawai,

            -- =====================================
            -- UNIT BULAN KEMARIN (>= 1.5 JT)
            -- =====================================
            SUM(
                CASE
                    WHEN h.Tanggal >= @AwalBulanLalu
                     AND h.Tanggal <= @TanggalPembandingBulanLalu
                     AND (d.Harga - d.Diskon) >= 1500000
                    THEN d.Jumlah
                    ELSE 0
                END
            ) AS UnitBulanKemarin,

            -- =====================================
            -- UNIT BULAN SEKARANG (>= 1.5 JT)
            -- =====================================
            SUM(
                CASE
                    WHEN h.Tanggal >= @AwalBulanIni
                     AND h.Tanggal <= @TanggalMinus2
                     AND (d.Harga - d.Diskon) >= 1500000
                    THEN d.Jumlah
                    ELSE 0
                END
            ) AS UnitBulanSekarang,

            -- TARGET
            ISNULL(p.Target, 0) AS Target,

            -- =====================================
            -- AKUMULASI ACHIEVE (%)
            -- =====================================
            CASE
                WHEN ISNULL(p.Target, 0) = 0 THEN 0
                ELSE
                    CAST(
                        (
                            SUM(
                                CASE
                                    WHEN h.Tanggal >= @AwalBulanIni
                                     AND h.Tanggal <= @TanggalMinus2
                                     AND (d.Harga - d.Diskon) >= 1500000
                                    THEN d.Jumlah
                                    ELSE 0
                                END
                            ) * 100.0
                        ) / p.Target
                    AS DECIMAL(10,2))
            END AS AkumulasiAchieve,

            -- =====================================
            -- PENJUALAN BULAN INI (SEMUA HARGA)
            -- =====================================
            CAST(
                SUM(
                    CASE
                        WHEN h.Tanggal >= @AwalBulanIni
                         AND h.Tanggal <= @TanggalMinus2
                        THEN (d.Harga - d.Diskon) * d.Jumlah
                        ELSE 0
                    END
                ) AS BIGINT
            ) AS PenjualanBulanIni,

            -- =====================================
            -- FEE BROKER
            -- =====================================
            CAST(
            SUM(
                CASE
                    WHEN h.Tanggal >= @AwalBulanIni
                     AND h.Tanggal <= @TanggalMinus2
                     AND (d.Harga - d.Diskon) >= 1500000
                    THEN ISNULL(d.RpBroker, 0)
                    ELSE 0
                END
            ) AS BIGINT
        ) AS FeeBroker

        FROM tHeaderPenjualanBarang h

        INNER JOIN tDetailPenjualanBarang d
            ON h.NoTransaksi = d.NoTransaksi
           AND d.Hapus = '0'

        INNER JOIN mPegawai p
            ON h.KodeSales = p.kode
           AND p.hapus = 0

        WHERE
            p.KodeJabatan = 8
            AND h.Tanggal >= @AwalBulanLalu
            AND h.Tanggal <= @TanggalMinus2

        GROUP BY
            p.nama,
            p.Target

        ORDER BY
            UnitBulanSekarang DESC;

        ";

        $rows = DB::connection('sqlsrv')->select($sql);

        return collect($rows)
            ->map(function ($row) {

                return [

                    'employee_name' =>
                        $row->NamaPegawai,

                    'last_month_units' =>
                        (float) $row->UnitBulanKemarin,

                    'current_month_units' =>
                        (float) $row->UnitBulanSekarang,

                    'target' =>
                        (float) $row->Target,

                    'achievement_percent' =>
                        (float) $row->AkumulasiAchieve,

                    'monthly_sales' =>
                        (float) $row->PenjualanBulanIni,

                    'broker_fee' =>
                        (float) $row->FeeBroker,
                ];
            })
            ->toArray();
    }
}