<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Combobox extends Component
{
    /**
     * Create a new component instance.
     */

    public $name;
    public $model;
    public $placeholder;
    public $options;

    public function __construct($name, $model, $placeholder, $options)
    {
        $this->name = $name;
        $this->model = $model;
        $this->placeholder = $placeholder;
        $this->options = $options;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.combobox.combobox');
    }
}
