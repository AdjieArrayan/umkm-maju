<?php

namespace App\View\Components\header;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class UserDropdown extends Component
{

    public function __construct()
    {

    }

    public function render(): View|Closure|string
    {
        return view('components.header.user-dropdown');
    }
}
