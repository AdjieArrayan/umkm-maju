<?php

namespace App\View\Components\Items;

use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Closure;

class ListItems extends Component
{
    public $items;

    public function __construct($items)
    {
        $this->items = $items;
    }

    public function render(): View|Closure|string
    {
        return view('components.items.list-items');
    }
}
