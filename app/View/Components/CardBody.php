<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CardBody extends Component
{
    public $id, $class;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($id = "", $class = "")
    {
        $this->id = $id;
        $this->class = $class;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.card-body');
    }
}
