<?php
/**
 * Created by IntelliJ IDEA.
 * User: shamim
 * Date: 4/18/18
 * Time: 10:01 PM
 */

namespace App\Data\Models;
use Illuminate\Database\Eloquent\Model;

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
}
