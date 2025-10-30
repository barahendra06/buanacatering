<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'city';
    protected $casts = [ 'province_id' => 'integer'];

    //------------------------------------------- MODEL RELATIONS -----------------------------------
    public function province()
    {
    	return $this->belongsTo('App\Province', 'province_id', 'id');
    }

    public function district()
    {
    	return $this->hasMany('App\District', 'city_id', 'id');
    }
    
    // one province has many student
    public function student()
    {
        return $this->hasMany('App\Student');
    }

    // one province has many student registration
    public function studentRegistration()
    {
        return $this->hasMany('App\StudentRegistration');
    }
}
