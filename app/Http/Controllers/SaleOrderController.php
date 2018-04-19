<?php

namespace App\Http\Controllers;

use App\Data\Models\Customer;
use App\Data\Models\Product;
use App\Data\Models\SaleOrder;
use App\Data\Models\SaleOrderItem;
use App\Domain\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaleOrderController extends Controller
{

    /**
     * @var ProductRepository
     */
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = SaleOrder::all();

        return view('saleOrders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = Product::all();
        $customers = Customer::all();
        return view('saleOrders.create', compact('customers', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return string
     */
    public function store(Request $request)
    {
        $customerId = $request->input('customerId');
        $due = $request->input('due');

        $userId = Auth::user()->id;
        DB::beginTransaction();
        try {
            $saleOrder = new SaleOrder(['customer_id' => $customerId, 'due' => $due, 'user_id' => $userId]);
            $saleOrder->save();

            foreach ($request->input('products') as $product) {
                $saleOrder->saleOrderItems()->save(new SaleOrderItem(['product_item_code' => $product['item_code'],
                    'qty' => $product['qty'],
                    'price' => $product['price']])
                );

                $selectedProduct = $this->productRepository->findById($product['item_code']);
                $selectedProduct->sale($product['qty']);
            }
            DB::commit();
            return "success";
        } catch (\Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
