<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TextArea extends Component
{
    public $name;
    public $placeholder;
    public $model;

    public function __construct($name, $placeholder, $model)
    {
        $this->name = $name;
        $this->placeholder = $placeholder;
        $this->model = $model;
    }
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.textfield.text-area');
    }
}
