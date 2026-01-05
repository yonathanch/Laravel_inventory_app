<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MetaItem extends Component
{
    /**
     * Create a new component instance.
     */
    //class = '' karna supaya jika kita tidak menggunakan komponen class maka ga error
    public $label, $value, $class;
    public function __construct($label, $value, $class = '')
    {
        $this->label = $label;
        $this->value = $value;
        $this->class = $class;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.meta-item');
    }
}
