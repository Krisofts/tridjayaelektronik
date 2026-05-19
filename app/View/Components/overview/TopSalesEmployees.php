<?php

namespace App\View\Components\Overview;

use Illuminate\View\Component;

class TopSalesEmployees extends Component
{
    public array $employees;

    public function __construct(array $employees = [])
    {
        $this->employees = $employees;
    }

    public function render()
    {
        return view('components.overview.top-sales-employees');
    }
}