<?php

namespace App\View\Components\Overview;

use App\Services\SalesSummaryService;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Log;

class TodayVsYesterday extends Component
{
    public array $today = [];
    public array $yesterday = [];
    public array $comparison = [];

    public function __construct(SalesSummaryService $service)
    {
        try {
            $data = $service->getTodayVsYesterday();

            $this->today = $data['today'] ?? [];
            $this->yesterday = $data['yesterday'] ?? [];
            $this->comparison = $data['comparison'] ?? [];

        } catch (\Throwable $e) {

            // 🔥 penting untuk production debugging
            Log::error('TodayVsYesterday Component Error', [
                'message' => $e->getMessage(),
            ]);

            // fallback aman biar UI tidak crash
            $this->today = [];
            $this->yesterday = [];
            $this->comparison = [];
        }
    }

    public function render(): View
    {
        return view('components.overview.today-vs-yesterday');
    }
}