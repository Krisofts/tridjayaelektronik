<?php

namespace App\View\Components\Overview;

use App\Services\DealerSalesService;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DealerSales extends Component
{
    public array $dealers;

    public function __construct(
        DealerSalesService $service
    ) {
        $this->dealers =
            $service->getDealerSales();
    }

    public function render(): View
    {
        return view(
            'components.overview.dealer-sales'
        );
    }
}