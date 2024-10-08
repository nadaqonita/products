<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Input extends Component
{
    public $type, $class, $id, $name, $placeholder;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($type = 'text', $class = 'form-control', $id = '', $name = '', $placeholder = '')
    {
        $this->type = $type;
        $this->class = $class;
        $this->id = $id;
        $this->name = $name;
        $this->placeholder = $placeholder;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.input');
    }
}
