<?php

namespace App\View\Components\dashboard;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ProductFlow extends Component
{

    public function __construct()
    {

    }

    public function render(): View|Closure|string
    {
        return view('components.dashboard.product-flow');
    }
}
