<?php

namespace App\Data\Models;

use Illuminate\Database\Eloquent\Model;

class SaleOrderItem extends Model
{

    protected $fillable = ['product_item_code', 'qty', 'price'];
    public function saleOrder(){
        return $this->belongsTo(SaleOrder::class);
    }

    public function product(){
        return $this->belongsTo(Product::class, 'product_item_code', 'item_code');
    }
}
