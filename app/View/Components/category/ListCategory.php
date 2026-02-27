<?php

namespace App\View\Components\categories;

use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Closure;

class ListCategory extends Component
{
    public $categories;

    public function __construct($categories)
    {

    }

    public function render(): View|Closure|string
    {
        return view('components.categories.list-categories');
    }
}
