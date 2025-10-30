<?php

namespace App\View\Components;

use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class InvoiceTypeSelect extends Component
{
    public $types = [];

    public $value = '';
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($value='')
    {
        $this->types = DB::table('invoice_item_type')->where('id', '<>', INVOICE_ITEM_TYPE_GOODS)->get();
        $this->value = $value;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.invoice-type-select');
    }
}
