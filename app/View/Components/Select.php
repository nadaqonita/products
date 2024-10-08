<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Select extends Component
{

    public $id, $class,$name;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($id = '', $class ='',$name)
    {
        $this->id = $id;
        $this->class = $class;
        $this->name = $name;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.select');
    }
}
