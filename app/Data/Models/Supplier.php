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
    protected $fillable = ['name'];
    public function products(){
        return $this->hasMany(Product::class);
    }
}