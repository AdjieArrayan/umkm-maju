<?php

namespace App\View\Components\common;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ThemeToggle extends Component
{
    public function __construct()
    {

    }

    public function render(): View|Closure|string
    {
        return view('components.common.theme-toggle');
    }
}
