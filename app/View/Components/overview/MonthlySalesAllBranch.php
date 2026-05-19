<?php

namespace App\View\Components\Overview;

use Illuminate\View\Component;

class MonthlySalesAllBranch extends Component
{
    public array $branches;

    public function __construct(array $branches = [])
    {
        $this->branches = $branches;
    }

    public function render()
    {
        return view('components.overview.monthly-sales-all-branch');
    }
}