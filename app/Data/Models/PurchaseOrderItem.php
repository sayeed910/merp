<?php

namespace App\Data\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItem extends Model
{
    protected $fillable = ['product_item_code', 'qty', 'cost'];
    public function purchaseOrder(){
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function product(){
        return $this->belongsTo(Product::class, 'product_item_code', 'item_code');
    }
}
