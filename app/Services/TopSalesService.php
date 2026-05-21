<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class TopSalesService
{
    public function getTopSales(): array
    {
        return Cache::store('redis')->remember(
            'sales:top-sales:' . now()->format('Y-m-d-H-i'),
            300,
            fn () => $this->fetch()
        );
    }

    private function fetch(): array
    {
        $sql = "
            DECLARE @startMonth DATE =
                DATEFROMPARTS(YEAR(GETDATE()), MONTH(GETDATE()), 1);

            DECLARE @nextMonth DATE =
                DATEADD(MONTH, 1, @startMonth);

            SELECT TOP 10

                p.kode AS KodePegawai,

                ISNULL(p.nama, '-') AS NamaPegawai,

                COUNT(DISTINCT h.NoTransaksi)
                    AS TotalTransaksi,

                SUM(ISNULL(d.Jumlah,0))
                    AS TotalQty,

                SUM(
                    ISNULL(d.Harga,0)
                    * ISNULL(d.Jumlah,0)
                ) AS TotalKotor,

                SUM(
                    ISNULL(d.Diskon,0)
                    * ISNULL(d.Jumlah,0)
                ) AS TotalDiskon,

                SUM(
                    (
                        ISNULL(d.Harga,0)
                        - ISNULL(d.Diskon,0)
                    )
                    * ISNULL(d.Jumlah,0)
                ) AS TotalPenjualan

            FROM tHeaderPenjualanBarang h

            INNER JOIN tDetailPenjualanBarang d
                ON h.NoTransaksi = d.NoTransaksi
                AND d.Hapus = '0'

            INNER JOIN mPegawai p
                ON h.KodeSales = p.kode

            WHERE h.Tanggal >= @startMonth
              AND h.Tanggal < @nextMonth

            GROUP BY
                p.kode,
                p.nama

            ORDER BY TotalPenjualan DESC
        ";

        $rows = DB::connection('sqlsrv')->select($sql);

        return collect($rows)
            ->map(function ($row) {

                return [

                    'code' => $row->KodePegawai,

                    'name' => $row->NamaPegawai,

                    'transactions' =>
                        (int) $row->TotalTransaksi,

                    'qty' =>
                        (float) $row->TotalQty,

                    'gross_sales' =>
                        (float) $row->TotalKotor,

                    'discount' =>
                        (float) $row->TotalDiskon,

                    'sales' =>
                        (float) $row->TotalPenjualan,

                    'aov' =>
                        (int) $row->TotalTransaksi > 0
                            ? round(
                                (float) $row->TotalPenjualan
                                / (int) $row->TotalTransaksi,
                                2
                            )
                            : 0,
                ];
            })
            ->toArray();
    }
}