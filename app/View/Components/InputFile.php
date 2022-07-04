<?php

namespace App\View\Components;

use Illuminate\View\Component;

class InputFile extends Component
{
    public $class;
    public $name;
    public $hasLabel;
    public $multiple;
    public $attrs;
    public $parentClass;
    public $required;
    public function __construct($name, $class = "", $hasLabel = true, $multiple = false, $attrs = "", $parentClass = "", bool $required = false)
    {
        $this->class = $class;
        $this->name = $name;
        $this->hasLabel = $hasLabel;
        $this->multiple = $multiple;
        $this->attrs = $attrs;
        $this->parentClass = $parentClass;
        $this->required = $required;
    }


    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.input-file');
    }
}
