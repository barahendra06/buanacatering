<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Student;

class Province extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'province';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    //------------------------------------------- MODEL RELATIONS -----------------------------------
    // one province has many city
    public function cities()
    {
    	return $this->hasMany('App\City', 'province_id', 'id');
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

    // one province has many father
    public function parentsFather()
    {
        return $this->hasMany('App\Parents', 'father_province_id');
    }

    // one province has many mother
    public function parentsMother()
    {
        return $this->hasMany('App\Parents', 'mother_province_id');
    }

    // one province has many guardian
    public function parentsGuardian()
    {
        return $this->hasMany('App\Parents', 'guardian_province_id');
    }
}
