<?php

namespace App\View\Components\Overview;

use App\Services\TopSalesService;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TopSales extends Component
{
    public array $sales = [];

    public function __construct(TopSalesService $service)
    {
        $this->sales = $service->getTopSales() ?? [];
    }

    public function render(): View
    {
        return view('components.overview.top-sales', [
            'sales' => $this->sales
        ]);
    }
}