<?php

namespace App\View\Components\Items;

use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Closure;

class EditItems extends Component
{
    public $items;

    public function __construct($items)
    {
        $this->items = $items;
    }

    public function render(): View|Closure|string
    {
        return view('components.items.edit-items');
    }
}
