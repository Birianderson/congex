<?php

namespace App\View\Components;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AppLayout extends Component
{

    public function __construct() {}
    /**
     * @return View
     */
    public function render()
    {
        return view('components.app-layout');
    }
}
