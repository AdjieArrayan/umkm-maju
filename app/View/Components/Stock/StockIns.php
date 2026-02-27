<?php

namespace App\View\Components\Stock\StockIns;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StockIn extends Component
{
    public $stockIns;

    public function __construct($stockIns)
    {
        $this->stockIns = $stockIns;
    }


    public function render(): View|Closure|string
    {
        return view('components.stock.stock-ins.list-stock-ins');
    }
}
