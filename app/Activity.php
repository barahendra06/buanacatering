<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'activity';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    // many activity is owned by one member
    public function member()
    {
        return $this->belongsTo('App\Member');
    }

    // many activity is owned by one content
    public function content()
    {
        return $this->belongsTo('App\Content');
    }

    // many activity is owned by one activity Type
    public function activityType()
    {
        return $this->belongsTo('App\ActivityType');
    }

    // one activity has one redeem transaction
    public function redemptionTransaction()
    {
        return $this->hasOne('App\RedemptionTransaction');
    }
}
