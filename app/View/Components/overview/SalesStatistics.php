<?php

namespace App\View\Components\Overview;

use Illuminate\View\Component;

class SalesStatistics extends Component
{
    /**
     * =========================
     * CHART DATA
     * =========================
     */
    public array $statistics;

    /**
     * =========================
     * ACTIVE RANGE
     * =========================
     */
    public string $range;

    /**
     * =========================
     * AVAILABLE RANGES
     * =========================
     */
    public array $ranges;

    public function __construct(
        array $statistics = [],
        string $range = 'monthly'
    ) {

        /**
         * =========================
         * VALID RANGE
         * =========================
         */
        $allowedRanges = [
            'daily',
            'weekly',
            'monthly'
        ];

        $this->range = in_array(
            $range,
            $allowedRanges
        )
            ? $range
            : 'monthly';

        /**
         * =========================
         * STATISTICS DATA
         * =========================
         */
        $this->statistics = $statistics;

        /**
         * =========================
         * RANGE BUTTONS
         * =========================
         */
        $this->ranges = [
            [
                'key' => 'daily',
                'label' => 'Daily'
            ],
            [
                'key' => 'weekly',
                'label' => 'Weekly'
            ],
            [
                'key' => 'monthly',
                'label' => 'Monthly'
            ],
        ];
    }

    /**
     * =========================
     * RENDER
     * =========================
     */
    public function render()
    {
        return view(
            'components.overview.sales-statistics'
        );
    }
}