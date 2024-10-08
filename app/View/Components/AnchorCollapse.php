<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AnchorCollapse extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */


    public $title, $icon,$id;
    public function __construct($title = '', $icon = '', $id='')
    {
        $this->title = $title;
        $this->icon = $icon;
        $this->id = $id;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.anchor-collapse');
    }
}
