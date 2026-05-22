<?php

namespace App\View\Components\Overview;

use App\Services\DailyDealerTargetService;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DailyDealerTarget extends Component
{
    public array $dealers;

    public function __construct(
        DailyDealerTargetService $service
    ) {
        $this->dealers = $service->get();
    }

    public function render(): View
    {
        return view('components.overview.daily-dealer-target');
    }
}