<?php

use App\Data\Models\User;
use Assert\Assertion;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\View\Factory;

/**
 * Class ProductControllerTest
 * Mr. Rafat is a user
 *
 */
class ReportControllerTest extends TestCase
{
    use DatabaseTransactions;
    use DatabaseMigrations;

    /** @var \App\Http\Controllers\ReportController */
    private $controller;



    /**
     * @test
     */
    public function purchaseBetween_should_return_purchase_orders_between_two_dates()
    {
        $viewmaker = resolve(Factory::class);
        $this->controller = new \App\Http\Controllers\ReportController($viewmaker);

        $todayOrder = factory(\App\Data\Models\PurchaseOrder::class)->create(['created_at' => '2018-04-20 05:07:08']);
        $tomorrowOrder = factory(\App\Data\Models\PurchaseOrder::class)->create(['created_at' => '2018-04-19 05:07:08']);

        $request = new \Illuminate\Http\Request(['date1' => '2018-04-19', 'date2' => '2018-04-20']);

        $orders = $this->controller->purchasesBetween($request);
        self::assertEquals(2, count($orders));
        $this->assertEquals($todayOrder->id, $orders[0]->id);
        $this->assertEquals($tomorrowOrder->id, $orders[1]->id);



        $this->post('/admin/reports/purchase', ['date1' => '2018-04-20', 'date2' => '2018-04-19']);
        fwrite(STDOUT, $this->response->content());
    }

    /**
     * @test
     */
    public function saleBetween_should_return_purchase_orders_between_two_dates()
    {
        $viewmaker = resolve(Factory::class);
        $this->controller = new \App\Http\Controllers\ReportController($viewmaker);

        $todayOrder = factory(\App\Data\Models\SaleOrder::class)->create(['created_at' => '2018-04-19 05:07:08']);
        $tomorrowOrder = factory(\App\Data\Models\SaleOrder::class)->create(['created_at' => '2018-04-20 05:07:08']);

        $request = new \Illuminate\Http\Request(['date1' => '2018-04-19', 'date2' => '2018-04-20']);

        $orders = $this->controller->salesBetween($request);
        self::assertEquals(2, count($orders));
        $this->assertEquals($todayOrder->id, $orders[0]->id);
        $this->assertEquals($tomorrowOrder->id, $orders[1]->id);
    }


}
