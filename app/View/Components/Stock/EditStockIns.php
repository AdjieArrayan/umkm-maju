<?php

namespace App\View\Components\Stock\StockIns;

use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Closure;

class EditStockIns extends Component
{
    public $StockIn;

    public function __construct($StockIn)
    {
        $this->StockIn = $StockIn;
    }

    public function render(): View|Closure|string
    {
        return view('components.stock.stock-ins.edit-stock-ins');
    }
}
