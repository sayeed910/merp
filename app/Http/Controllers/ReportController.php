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

        return PurchaseOrder::whereRaw('date(created_at) between ? and ?',
            [$date1, $date2])->get();

    }

    /**
     * returns all the sale orders between two given dates
     * @param Request $request
     */
    public function salesBetween(Request $request)
    {
        $date1 = date($request->input('date1'));
        $date2 = date($request->input('date2'));

        return SaleOrder::whereRaw('date(created_at) between ? and ?',
            [$date1, $date2])->get();
    }
}
