<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Week extends Model
{
    use HasFactory;

    protected $table = 'week';

    public function month()
    {
        return $this->belongsTo('App\Month');
    }

    public function weeklySkillIndicators()
    {
        return $this->belongsToMany('App\SkillIndicator', 'weekly_curriculum_schedule', 'week_id', 'skill_indicator_id')->withPivot('week_id','stage_id','branch_id','level_id')->withTimestamps();
    }

    public function levels()
    {
        return $this->belongsToMany('App\Level', 'weekly_curriculum_schedule', 'week_id', 'level_id')->withTimestamps();
    }
}
