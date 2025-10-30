<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Month extends Model
{
    use HasFactory;

    protected $table = 'month';

    public function weeks()
    {
        return $this->hasMany('App\Week');
    }
}
