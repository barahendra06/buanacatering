<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'notification';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    protected $appends = ['created_at_view', 'created_at_date_indonesian'];

    protected $casts = [
        'date' => 'datetime',
    ];

    protected $fillable = [
        'notification_type_id',
        'recipient_id',
        'sender_id',
        'created_by',
        'activity_type_id',
        'title',
        'text',
        'button_text',
        'link',
        'featured_image_path',
        'date',
        'is_read'
    ];

    public function notificationType()
    {
        return $this->belongsTo(NotificationType::class);
    }

    public function recipient()
    {
        return $this->belongsTo('App\User', 'recipient_id');
    }

    public function sender()
    {
        return $this->belongsTo('App\User', 'sender_id');
    }


    public function scopeNotRead($query)
    {
        return $query->where('is_read', 0);
    }

    public function is_read()
    {
        if($this->is_read == 0)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    public function getCreatedAtViewAttribute()
    {
        if($this->created_at)
        {
            return $this->created_at->diffForHumans();
        }
        else
        {
            return "0";
        }
    }
}
