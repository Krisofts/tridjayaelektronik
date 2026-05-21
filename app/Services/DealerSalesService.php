<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DealerSalesService
{
    public function getDealerSales(): array
    {
        return Cache::store('redis')->remember(
            'sales:dealer:' . now()->format('Y-m-d-H-i'),
            300,
            fn () => $this->fetch()
        );
    }

    private function fetch(): array
    {
        $sql = "
            DECLARE @startMonth DATE =
                DATEFROMPARTS(YEAR(GETDATE()), MONTH(GETDATE()), 1);

            DECLARE @tomorrow DATE =
                DATEADD(DAY, 1, CAST(GETDATE() AS DATE));

            SELECT

                h.KodeDealer,

                ISNULL(md.Nama, '-') AS NamaDealer,

                h.KodeCabang,

                ISNULL(mc.Nama, '-') AS NamaCabang,

                COUNT(DISTINCT h.NoTransaksi) AS TotalTransaksi,

                SUM(ISNULL(d.Jumlah,0)) AS TotalUnit,

                SUM(ISNULL(d.Jumlah,0) * ISNULL(d.Harga,0))
                    AS TotalHarga,

                SUM(ISNULL(d.Diskon,0))
                    AS TotalDiskon,

                SUM(
                    (ISNULL(d.Jumlah,0) * ISNULL(d.Harga,0))
                    - ISNULL(d.Diskon,0)
                ) AS TotalSales

            FROM tHeaderPenjualanBarang h

            INNER JOIN tDetailPenjualanBarang d
                ON d.NoTransaksi = h.NoTransaksi
                AND d.Hapus = '0'

            LEFT JOIN mDealer md
                ON h.KodeDealer = md.Kode

            LEFT JOIN mCabang mc
                ON h.KodeCabang = mc.Kode

            WHERE h.Tanggal >= @startMonth
              AND h.Tanggal < @tomorrow

            GROUP BY
                h.KodeDealer,
                md.Nama,
                h.KodeCabang,
                mc.Nama

            ORDER BY TotalSales DESC
        ";

        $rows = DB::connection('sqlsrv')->select($sql);

        return collect($rows)
            ->map(function ($row) {

                $sales = (float) $row->TotalSales;
                $trx   = (int) $row->TotalTransaksi;

                return [

                    'dealer_code' => $row->KodeDealer,

                    'dealer_name' => $row->NamaDealer,

                    'branch_code' => $row->KodeCabang,

                    'branch_name' => $row->NamaCabang,

                    'transactions' => $trx,

                    'units' => (float) $row->TotalUnit,

                    'gross_sales' => (float) $row->TotalHarga,

                    'discount' => (float) $row->TotalDiskon,

                    'net_sales' => $sales,

                    'aov' => $trx > 0
                        ? round($sales / $trx, 2)
                        : 0,
                ];
            })
            ->toArray();
    }
}