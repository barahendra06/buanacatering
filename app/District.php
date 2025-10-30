<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $table = 'district';

    //------------------------------------------- MODEL RELATIONS -----------------------------------
    public function city()
    {
    	return $this->belongsTo('App\City', 'city_id', 'id');
    }

    public function schools()
    {
    	return $this->hasMany('App\School', 'code', 'kec_code');
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
