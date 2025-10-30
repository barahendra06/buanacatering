<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivityType extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'activity_type';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    // one activity type has many activity
    public function activity()
    {
        return $this->hasMany('App\Activity');
    }


    public function badge()
    {
        return $this->hasOne('App\Badge');
    }
}
