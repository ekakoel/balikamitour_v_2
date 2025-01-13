<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Input extends Component
{
    public $id;
    public $name;
    public $label;
    public $placeholder;
    public $min;
    public $max;
    public $value;
    public $type;

    public function __construct($id, $name, $label, $placeholder = '',$min = '', $max = '', $value = '', $type = '')
    {
        $this->id = $id;
        $this->name = $name;
        $this->label = $label;
        $this->placeholder = $placeholder;
        $this->min = $min;
        $this->max = $max;
        $this->value = $value;
        $this->type = $type;
    }

    public function render()
    {
        return view('components.input');
    }
}
