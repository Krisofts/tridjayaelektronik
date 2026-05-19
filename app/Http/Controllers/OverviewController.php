<?php

namespace App\Http\Controllers;

use App\Services\SalesReportService;

class OverviewController extends Controller
{
    public function __construct(
        protected SalesReportService $salesService
    ) {}

    public function index()
    {
        $today = $this->salesService->getTodaySalesReport();
        $yesterday = $this->salesService->getYesterdaySalesReport();
        $month = $this->salesService->getMonthSalesReport();

        // 🏢 ALL CABANG BULAN INI
        $monthlyByDealer = $this->salesService->getMonthlySalesByDealer();

        // 🏆 TOP SALES EMPLOYEE
        $topSalesEmployees = $this->salesService->getTopSalesEmployees();

        // 🎯 monthly target (bisa nanti dari DB / settings)
        $monthlyTarget = 10363290610;

        return view('pages.dashboard.overview', [
            'title' => 'Dashboard Overview',

            // daily comparison
            'comparison' => $this->buildComparison($today, $yesterday),

            // monthly summary + target
            'monthly' => $this->buildMonthly($month, $monthlyTarget),

            // 🏢 all branch
            'monthlyByDealer' => $monthlyByDealer,

            // 🏆 top employee
            'topSalesEmployees' => $topSalesEmployees,
        ]);
    }

    /**
     * =========================
     * TODAY VS YESTERDAY
     * =========================
     */
    private function buildComparison($today, $yesterday): array
    {
        $todayTransaksi = (int) ($today->TotalTransaksi ?? 0);
        $yesterdayTransaksi = (int) ($yesterday->TotalTransaksi ?? 0);

        $todayUnit = (int) ($today->TotalUnit ?? 0);
        $yesterdayUnit = (int) ($yesterday->TotalUnit ?? 0);

        $todaySales = (float) ($today->TotalSetelahDiskon ?? 0);
        $yesterdaySales = (float) ($yesterday->TotalSetelahDiskon ?? 0);

        return [
            'transaksi' => $this->makeMetric($todayTransaksi, $yesterdayTransaksi),
            'unit'      => $this->makeMetric($todayUnit, $yesterdayUnit),
            'sales'     => $this->makeMetric($todaySales, $yesterdaySales),
        ];
    }

    /**
     * =========================
     * MONTHLY + TARGET
     * =========================
     */
    private function buildMonthly($month, float $target): array
    {
        $sales = (float) ($month->TotalSetelahDiskon ?? 0);

        $progress = $target > 0
            ? ($sales / $target) * 100
            : 0;

        return [
            'sales' => $sales,
            'target' => $target,
            'remaining' => max($target - $sales, 0),
            'progress' => $progress,
        ];
    }

    /**
     * =========================
     * GENERIC METRIC
     * =========================
     */
    private function makeMetric($today, $yesterday): array
    {
        $diff = $today - $yesterday;

        $growth = $yesterday > 0
            ? (($today - $yesterday) / $yesterday) * 100
            : 0;

        return [
            'today' => $today,
            'yesterday' => $yesterday,
            'diff' => $diff,
            'growth' => $growth,
        ];
    }
}