<?php

namespace Dizatech\Identifier\View\Components;

use Illuminate\View\Component;

class LoginComponent extends Component
{
    public $page;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($page)
    {
        $this->page = $page;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('vendor.dizatech-identifier.components.login-component');
    }
}
