<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Button extends Component
{
    public $type, $class, $id, $attr;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->attr = $attr;
        // $this->class = $class;
        // $this->id = $id;
        // $this->type = $type;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.button');
    }
}
