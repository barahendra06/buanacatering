<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Carbon\Carbon;

class HseElementImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public $collection = [];

    public function collection(Collection $collection)
    {
        $this->collection = $collection;

        return $this->collection;
    }

}
