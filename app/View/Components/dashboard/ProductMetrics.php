<?php

namespace App\View\Components\dashboard;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ProductMetrics extends Component
{
    public $totalItems;
    public $totalStock;

    public function __construct($totalItems, $totalStock)
    {
        $this->totalItems = $totalItems;
        $this->totalStock = $totalStock;
    }

    public function render()
    {
        return view('components.dashboard.product-metrics');
    }
}