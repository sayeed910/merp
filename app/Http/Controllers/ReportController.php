<?php

namespace App\Http\Controllers;

use App\Data\Models\PurchaseOrder;
use App\Data\Models\SaleOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\View\Factory as ViewFactory;

class ReportController extends Controller
{
    /**
     * @var ViewFactory
     */
    private $viewFactory;

    public function __construct(ViewFactory $viewFactory)
    {

        $this->viewFactory = $viewFactory;
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->viewFactory->make('reports.index');
    }

    /**
     * returns all the purchase orders between two given dates
     * @param Request $request
     */
    public function purchasesBetween(Request $request)
    {
        $date1 = date($request->input('date1'));
        $date2 = date($request->input('date2'));

        $orders = PurchaseOrder::whereRaw('date(created_at) between ? and ?',
            [$date1, $date2])->get();

        foreach ($orders as $order){
            $order->amount = $order->amount();
            $order->user;
            $order->supplier;
//            $order->username = $order->user->name;
//            $order->suppliername = $order->supplier->name;
        }

        return $orders;

    }

    /**
     * returns all the sale orders between two given dates
     * @param Request $request
     */
    public function salesBetween(Request $request)
    {
        $date1 = date($request->input('date1'));
        $date2 = date($request->input('date2'));

        $orders = SaleOrder::whereRaw('date(created_at) between ? and ?',
            [$date1, $date2])->get();

        foreach ($orders as $order){
            $order->amount = $order->amount();
            $order->user;
            $order->customer;
        }

        return $orders;
    }
}
