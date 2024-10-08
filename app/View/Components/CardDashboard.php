<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CardDashboard extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $title, $desc, $class, $id;

    public function __construct($title = '', $desc = '', $class = '', $id = '')
    {
        $this->title = $title;
        $this->desc = $desc;
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
        return view('components.card-dashboard');
    }
}
