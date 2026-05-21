<?php

namespace App\View\Components\Overview;

use App\Services\MonthlyTargetService;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MonthlyTarget extends Component
{
    public array $summary;

    public function __construct(
        MonthlyTargetService $service
    ) {
        $this->summary =
            $service->getMonthlyTargetSummary();
    }

    public function render(): View
    {
        return view(
            'components.overview.monthly-target'
        );
    }
}