<?php
/**
 * Created by IntelliJ IDEA.
 * User: shamim
 * Date: 4/18/18
 * Time: 10:02 PM
 */

namespace App\Data\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Supplier extends Model
{
    public function purchaseOrders(){
        return $this->hasMany(PurchaseOrder::class);
    }
    public function due(){
        $due = 0;
        foreach ($this->purchaseOrders as $purchaseOrder) {
            $due += $purchaseOrder->due;
        }

        return $due;
    }
    public function purchaseInYear($year)
    {
        $query = "SELECT MONTH(created_at) as month, count(*) as _count from purchase_orders WHERE supplier_id = ? and YEAR(created_at) = ? group by MONTH(created_at) ";
        return DB::select(DB::raw($query), [$this->id, $year]);
    }
}
