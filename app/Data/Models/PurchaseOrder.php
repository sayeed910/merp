<?php

namespace App\Data\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $fillable = ['supplier_id', 'due', "user_id"];
    public function purchaseOrderItems()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    public function amount(){
        $sum = 0;
        foreach($this->purchaseOrderItems as $item){
            $sum += $item->qty * $item->product->price;
        }
        return $sum;
    }
}
