<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TextField1 extends Component
{
    /**
     * Create a new component instance.
     */


    public $name;
    public $placeholder;
    public $model;
    public $type;
    public $label;
    public $value;
    public $isReadOnly;

    public function __construct($name, $placeholder, $model, $type = 'text', $label ="", $value = "", $isReadOnly = false)
    {
        $this->name = $name;
        $this->placeholder = $placeholder;
        $this->model = $model;
        $this->type = $type;
        $this->label = $label;
        $this->value = $value;
        $this->isReadOnly = $isReadOnly;
    }

    public function render(): View|Closure|string
    {
        return view('components.textfield.text-field1');
    }
}
