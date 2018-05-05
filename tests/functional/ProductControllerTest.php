<?php

use App\Data\Models\User;
use Assert\Assertion;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class ProductControllerTest
 * Mr. Rafat is a user
 *
 */
class ProductControllerTest extends TestCase
{
    use DatabaseTransactions;
    use DatabaseMigrations;

    //When he visits /admin/products

    /**
     * @test
     * Given he is not authenticated
     */
    public function it_should_redirect_unauthenticated_user_to_login_page()
    {
        $this->visit('/admin/products')
             ->seePageIs('/login');

    }

    //Given he is authenticated

    /**
     * @test
     *
     */
    public function it_should_have_itemcode_in_column_header()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user)
             ->visit('/admin/products')
             ->see('<th>ItemCode</th>');
    }

    /**
     * @test
     *
     */
    public function it_should_display_the_itemcode_of_products()
    {
        $user = factory(User::class)->create();
        $product = factory(\App\Data\Models\Product::class)->create();
        $itemCode = $product->item_code;
        $this->assertNotNull($itemCode);
        $this->actingAs($user)
             ->visit('/admin/products')
             ->see($itemCode);
    }


    // When he does a post request to /admin/products

    /**
     * @test
     * Given he provided all attributes correctly
     */
    public function store_should_create_new_product()
    {
        factory(\App\Data\Models\Category::class)->create();
        factory(\App\Data\Models\Brand::class)->create();

        $data = ['item_code' => 'Pro-1002', 'name' => 'a_product', 'brand_id' => 1, 'unit' => 'abc',
            'size' => 'abc', 'category_id' => 1, 'cost' => 40.25, 'price' => 42.25];

        $this->authenticate()->post('/admin/products', $data);

        \App\Data\Models\Product::where('name', 'a_product')->firstOrFail();


    }

    /**
     * @test
     *
     */
    public function update_should_update_the_requested_product()
    {
        $productModel = factory(\App\Data\Models\Product::class)->create();
        factory(\App\Data\Models\Brand::class)->create();

        $this->authenticate()->patch('/admin/products/' . $productModel->item_code, ['name' => 'a_product']);
        self::assertEquals('a_product', \App\Data\Models\Product::find($productModel->item_code)->name);

        $this->patch('/admin/products/' . $productModel->item_code, ['name' => 'b_product', 'brand_id' => 1]);
        self::assertEquals('b_product', \App\Data\Models\Product::find($productModel->item_code)->name);
        self::assertEquals(1, \App\Data\Models\Product::find($productModel->item_code)->brand->id);

    }

    /**
     * @test
     *
     */
    public function delete_should_remove_the_requested_product()
    {
        $productModel = factory(\App\Data\Models\Product::class)->create();

        $this->authenticate()->delete('/admin/products/' . $productModel->item_code);
        $this->assertNull(\App\Data\Models\Product::find($productModel->item_code));
    }


    /**
     * @test
     *
     */
    public function after_delete_product_should_not_appear_in_the_index_page()
    {
        $product = factory(\App\Data\Models\Product::class)->create();
        $this->authenticate()->visit('/admin/products')->see($product->item_code);

        $this->delete('/admin/products/' . $product->item_code)
             ->dontSee($product->item_code);
        $this->assertEquals(json_encode(['success' => true]), $this->response->content());

    }

    /**
    * @test
    *
    */
    public function it_should_show_top_10(){
        $this->authenticate();
        factory(\App\Data\Models\Product::class, 100)->create();
        factory(\App\Data\Models\SaleOrder::class, 30)->create();
        factory(\App\Data\Models\SaleOrderItem::class, 100)->create();

//        $this->get("/admin/products/top10", ['date1' => "2018-05-01", "date2" => "2018-05-10"]);
        $this->call("GET", "/admin/products/top10", ['date1' => "2018-05-01", "date2" => "2018-05-10"]);
        fwrite(STDOUT, $this->response->content());
    }



}
