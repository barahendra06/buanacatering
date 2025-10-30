<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Auth;

class DiscountInput extends Component
{
    public $function;
    public $name;
    public $label;
    public $availableDiscounts;
    public $user;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string $function, $availableDiscounts = null, $label = '', string $name = 'discount', bool $hideLabel = false)
    {
        $this->function = $function;
        $this->name = $name;
        $this->label = $label;
        $this->availableDiscounts = $availableDiscounts;

        if(Auth::check())
        {
            $this->user = Auth::user();
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.discount-input');
    }
}
