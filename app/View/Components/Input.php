<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Input extends Component
{
    public string $type;
    public string $name;
    public ?string $label;
    public ?string $value;
    public ?string $placeholder;
    public bool $required;
    public string $class;
    public function __construct(
        string $name,
        string $type = 'text',
        string $label = null,
        string $value = null,
        string $placeholder = null,
        bool $required = false,
        string $class = ''
    ) {
        $this->name = $name;
        $this->type = $type;
        $this->label = $label;
        $this->value = $value;
        $this->placeholder = $placeholder;
        $this->required = $required;
        $this->class = $class;
    }

    public function render()
    {
        return view('components.input');
    }
}
