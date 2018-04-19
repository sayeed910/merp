<?php
/**
 * Created by IntelliJ IDEA.
 * User: shamim
 * Date: 4/18/18
 * Time: 10:02 PM
 */

namespace App\Data\Models;
use Illuminate\Database\Eloquent\Model;

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
}
