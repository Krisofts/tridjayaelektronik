<?php

namespace App\View\Components\Overview;

use Illuminate\View\Component;

class YearlySalesByMonth extends Component
{
    public array $yearlySalesByMonth;

    public function __construct(array $yearlySalesByMonth = [])
    {
        $this->yearlySalesByMonth = $yearlySalesByMonth;
    }

    public function render()
    {
        return view('components.overview.yearly-sales-by-month');
    }
}