<?php

namespace App\View\Components\categories;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AddCategory extends Component
{
    public $categories;

    public function __construct($categories)
    {
        $this->categories = $categories;
    }

    public function render()
    {
        return view('components.categories.add-categories-form');
    }
}

