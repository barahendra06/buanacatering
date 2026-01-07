<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CateringOrderHistory extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'catering_order_history';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'catering_order_id',
        'title',
        'description',
        'old_catering_order_status_id',
        'new_catering_order_status_id',
        'updated_by_id',
        'is_visble'
    ];

    public function cateringOrder()
    {
        return $this->belongsTo('App\CateringOrder');
    }

    public function oldCateringOrderStatus()
    {
        return $this->belongsTo('App\CateringOrderStatus', 'old_catering_order_status_id');
    }

    public function newCateringOrderStatus()
    {
        return $this->belongsTo('App\CateringOrderStatus', 'new_catering_order_status_id');
    }

    public function updatedBy()
    {
        return $this->belongsTo('App\User', 'updated_by_id');
    }
}
