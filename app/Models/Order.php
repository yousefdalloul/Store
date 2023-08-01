<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\This;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id','user_id','payment_method','status','payment_status',
    ];
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'name'=>'Guest Customer'
        ]);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, '','order_id','product_id','id','id')
            ->using(OrderItem::class)
            ->as('order_item ')
            ->withPivot([
                'product_name','price','quantity','options',
        ]);
    }
    public function addresses()
    {
        return $this->hasMany(OrderAddress::class);
    }

    public function billingAddress()
    {
        return $this->hasOne(OrderAddress::class,'order_id','id')
            ->where('type','=','billing');
    }

    public function ShippingAddress()
    {
        return $this->hasOne(OrderAddress::class,'order_id','id')
            ->where('type','=','shipping');
    }
    protected static function booted()
    {
        static::create(function (Order $order){
            //20230001,20230002
            $order->number = Order::getMaxOrderNumber();
        });
    }
    public static function getMaxOrderNumber()
    {
        $year = Carbon::now()->year;
        $number = Order::whereYear('created_at',$year)->max('number');
        if ($number){
            return $number + 1;
        }
        //first_order
        return $year . '0001';
    }

}
