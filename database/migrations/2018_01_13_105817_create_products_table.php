<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->string('item_code');
            $table->string('name');
            $table->string('unit');
            $table->string('size');
            $table->integer('brand_id');
            $table->integer('category_id');
            $table->integer('cost');
            $table->integer('price');
            $table->integer('stock');
            $table->integer('damaged');
            $table->integer('returned')->default(0);
            $table->timestamps();


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
