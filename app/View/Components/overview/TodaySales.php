<?php

namespace App\View\Components\Overview;

use Illuminate\View\Component;

class TodaySales extends Component
{
    public array $comparison;

    public function __construct(array $comparison = [])
    {
        $this->comparison = $comparison;
    }

    public function render()
    {
        return view('components.overview.today-sales');
    }
}