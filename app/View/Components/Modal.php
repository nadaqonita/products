<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Modal extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $id, $title, $dialogClass;
    public function __construct($id, $title, $dialogClass = '')
    {
        $this->id = $id;
        $this->title = $title;
        $this->dialogClass = $dialogClass;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.modal');
    }
}
