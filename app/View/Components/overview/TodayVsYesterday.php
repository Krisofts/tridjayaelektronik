<?php

namespace App\View\Components\Overview;

use App\Services\SalesSummaryService;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TodayVsYesterday extends Component
{
    public array $today;
    public array $yesterday;
    public array $comparison;

    public function __construct(SalesSummaryService $service)
    {
        $data = $service->getTodayVsYesterday();

        $this->today = $data['today'];
        $this->yesterday = $data['yesterday'];
        $this->comparison = $data['comparison'];
    }

    public function render(): View
    {
        return view('components.overview.today-vs-yesterday');
    }
}