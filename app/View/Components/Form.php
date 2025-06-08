<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Form extends Component
{
    public string $action;
    public string $method;
    public ?string $enctype;

    public function __construct($action = '#', $method = 'POST', $enctype = null)
    {
        $this->action = $action;
        $this->method = strtoupper($method);
        $this->enctype = $enctype;
    }

    public function render()
    {
        return view('components.form');
    }
}
