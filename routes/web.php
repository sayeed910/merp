<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('home');
});

Auth::routes();

Route::get('/admin', 'HomeController@index');

Route::post("/admin/products/{product}", "ProductController@json");
Route::post("/admin/products", "ProductController@store");
Route::get("/admin/products", "ProductController@index");
Route::get("/admin/products/create", "ProductController@create");
Route::get("/admin/products/{itemCode}/edit", "ProductController@edit");
Route::patch("/admin/products/{itemCode}", "ProductController@update");
Route::delete("/admin/products/{itemCode}", "ProductController@destroy");

//Sale order routes
Route::get('/admin/sale-orders', 'SaleOrderController@index');
Route::get("/admin/sale-orders/create", "SaleOrderController@create");
Route::post("/admin/sale-orders", "SaleOrderController@store");

//Sale order routes
Route::get('/admin/purchase-orders', 'PurchaseOrderController@index');
Route::get("/admin/purchase-orders/create", "PurchaseOrderController@create");
Route::post("/admin/purchase-orders", "PurchaseOrderController@store");

Route::get("/admin/brands", "BrandController@index");
Route::post("/admin/brands", "BrandController@store");
Route::delete("/admin/brands/{id}", "BrandController@destroy");
Route::patch("/admin/brands/{id}", "BrandController@update");

//Category Routes
Route::get("/admin/categories", "CategoryController@index");
Route::post("/admin/categories", "CategoryController@store");
Route::delete("/admin/categories/{id}", "CategoryController@destroy");
Route::patch("/admin/categories/{id}", "CategoryController@update");

Route::get("/admin/customers", "CustomerController@index");
Route::post("/admin/customers", "CustomerController@store");
Route::delete("/admin/customers/{id}", "CustomerController@destroy");
Route::patch("/admin/customers/{id}", "CustomerController@update");

//Supplier routes
Route::get("/admin/suppliers", "SupplierController@index");
Route::post("/admin/suppliers", "SupplierController@store");
Route::delete("/admin/suppliers/{id}", "SupplierController@destroy");
Route::patch("/admin/suppliers/{id}", "SupplierController@update");



//EloquentEmployee routes
Route::get('/admin/employees', 'EmployeeController@index');
Route::get("/admin/employees/create", "EmployeeController@create");
