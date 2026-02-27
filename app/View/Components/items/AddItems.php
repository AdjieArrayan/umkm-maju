<?php

namespace App\View\Components\items;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AddItems extends Component
{
    public $categories;

    public function __construct($categories)
    {
        $this->categories = $categories;
    }

    public function render()
    {
        return view('components.items.add-items-form');
    }
}

