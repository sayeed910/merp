<?php

namespace App\Data\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['item_code', 'name', 'unit', 'brand_id', 'cost', 'price', 'size'];
    public $incrementing = false;
    protected $primaryKey = 'item_code';
    protected $keyType = 'string';

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function saleOrderItems()
    {
        return $this->hasMany(SaleOrderItem::class, 'product_item_code');
    }

    public function description()
    {
        return $this->name . " " . $this->brand->name . " " . $this->size;
    }
}
