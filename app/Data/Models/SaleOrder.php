<?php

namespace App\Data\Models;

use Illuminate\Database\Eloquent\Model;
use function Symfony\Component\Debug\Tests\testHeader;

class SaleOrder extends Model
{
    protected $fillable = ['customer_id', 'due', "user_id"];
    public function saleOrderItems()
    {
        return $this->hasMany(SaleOrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function amount(){
        $sum = 0;
        foreach($this->saleOrderItems as $item){
            $sum += $item->qty * $item->product->price;
        }
        return $sum;
    }
}
