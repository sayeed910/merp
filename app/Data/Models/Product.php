<?php

namespace App\Data\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public function scopeTop10($query, $date1, $date2){
        $str = <<<QUERY
select  * from products, (
select product_item_code, count(product_item_code) as sale_count from sale_order_items
where date(created_at) between date(?) and date(?) 
GROUP by product_item_code) as t 
where product_item_code = item_code order by  sale_count desc LIMIT 10
QUERY;

        $query = DB::raw($str);
        $top10 = Product::hydrateRaw($query, [$date1, $date2]);
        return $top10;
    }

    public function saleInYear($year){
        $queryStr = "SELECT MONTH(created_at) as month, sum(qty) as qty_sum from sale_order_items WHERE product_item_code= ? and YEAR(created_at) = ? group by MONTH(created_at)";

        return DB::select(DB::raw($queryStr), [$this->item_code, $year]);
    }

    public function description()
    {
        return $this->name . " " . $this->brand->name . " " . $this->size;
    }
}
