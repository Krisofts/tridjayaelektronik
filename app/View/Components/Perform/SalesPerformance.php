<?php

namespace App\View\Components\Perform;

use App\Services\SalesPerformanceService;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SalesPerformance extends Component
{
    public array $sales;

    public function __construct(
        SalesPerformanceService $service
    ) {
        $this->sales =
            $service->getSalesPerformance();
    }

    public function render(): View
    {
        return view(
            'components.perform.sales-performance'
        );
    }
}