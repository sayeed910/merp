<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Data\Models\User::class)->create();
        factory(App\Data\Models\Brand::class, 10)->create();
        factory(App\Data\Models\Category::class, 10)->create();
        factory(App\Data\Models\Customer::class, 100)->create();
        factory(App\Data\Models\Supplier::class, 100)->create();
        factory(App\Data\Models\Product::class, 100)->create();

        factory(App\Data\Models\SaleOrder::class, 100)->create();
        factory(App\Data\Models\SaleOrderItem::class, 500)->create();

        factory(App\Data\Models\PurchaseOrder::class, 100)->create();
        factory(App\Data\Models\PurchaseOrderItem::class, 500)->create();

    }
}
