<?php
/**
 * Created by IntelliJ IDEA.
 * User: shamim
 * Date: 4/18/18
 * Time: 10:01 PM
 */

namespace App\Data\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Customer extends Model
{
    public function saleOrders(){
        return $this->hasMany(SaleOrder::class);
    }
    public function due(){
        $due = 0;
        foreach ($this->saleOrders as $saleOrder) {
            $due += $saleOrder->due;
        }

        return $due;
    }

    public function purchaseInYear($year)
    {
        $query = "SELECT MONTH(created_at) as month, count(*) as _count from sale_orders WHERE customer_id = ? and YEAR(created_at) = ? group by MONTH(created_at) ";
        return DB::select(DB::raw($query), [$this->id, $year]);
    }
}
