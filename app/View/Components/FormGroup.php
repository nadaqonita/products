<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormGroup extends Component
{

    public $id, $label;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($id = '', $label = '')
    {
        $this->id = $id;
        $this->label = $label;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.form-group');
    }
}
