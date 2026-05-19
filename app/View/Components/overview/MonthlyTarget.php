<?php

namespace App\View\Components\Overview;

use Illuminate\View\Component;

class MonthlyTarget extends Component
{
    public array $monthly;

    public function __construct(array $monthly = [])
    {
        $this->monthly = $monthly;
    }

    public function render()
    {
        return view('components.overview.monthly-target');
    }
}