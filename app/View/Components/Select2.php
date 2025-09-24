<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Select2 extends Component
{
    public function __construct(public mixed $options, public mixed $selectedOptions) {}

    public function render(): View
    {
        return view('components.select2');
    }
}
