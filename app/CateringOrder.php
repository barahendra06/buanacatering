<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CateringOrder extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'catering_orders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_number',
        'user_id',
        'catering_customer_id',
        'subtotal',
        'catering_order_status_id',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 
        'updated_at', 
        'completed_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'completed_at' => 'datetime',
    ];

    protected $appends = ['date_view','total_item_orders'];


    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function cateringCustomer()
    {
        return $this->belongsTo('App\CateringCustomer', 'catering_customer_id');
    }

    public function cateringOrderDetails()
    {
        return $this->hasMany('App\CateringOrderDetail');
    }

    public function cateringOrderStatus()
    {
        return $this->belongsTo('App\CateringOrderStatus');
    }

    public function paymentMethod()
    {
        return $this->belongsTo('App\PaymentMethod');
    }


    /**
     * Scope a query to only include notExpired
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotExpired($query)
    {
        return $query->where('catering_order_status_id', '!=', STORE_ORDER_STATUS_EXPIRED);
    }

    /**
     * Scope a query to only include 
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLocked($query)
    {
        return $query->where('catering_order_status_id', '>', STORE_ORDER_STATUS_INITIAL)
                    ->where('catering_order_status_id', '<', STORE_ORDER_STATUS_COMPLETED);
    }

    public function isNotLocked()
    {
        return $this->catering_order_status_id == STORE_ORDER_STATUS_INITIAL;
    }

    public function isLocked()
    {
        return $this->catering_order_status_id != STORE_ORDER_STATUS_INITIAL && $this->catering_order_status_id != STORE_ORDER_STATUS_EXPIRED;
    }

    public function isExpired(): bool
    {
        return $this->catering_order_status_id == STORE_ORDER_STATUS_EXPIRED;
    }

    public function getDateViewAttribute()
    {
        return $this->created_at->format('d-m-Y H:i:s');
    }

    public function getTotalItemOrdersAttribute()
    {
        $totalItem =  $this->cateringOrderDetails->sum('quantity').' '. ($this->cateringOrderDetails->sum('quantity') > 1 ? 'Items' : 'Item');

        return $totalItem;
    }

    public function cateringOrderHistories()
    {
        return $this->hasMany('App\CateringOrderHistory');
    }

    public function getSubtotalRupiahAttribute()
    {
        return "Rp " . number_format($this->subtotal,0,',','.');
    }

    public function scopeCompleted($query)
    {
        return $query->where('catering_order_status_id', STORE_ORDER_STATUS_COMPLETED);
    }
}
