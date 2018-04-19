<?php

namespace App\Http\Controllers;

use App\Data\Models\Product;
use App\Data\Models\PurchaseOrder;
use App\Data\Models\PurchaseOrderItem;
use App\Data\Models\Supplier;
use App\Domain\ProductRepository;
use App\Domain\PurchaseOrderRepository;
use App\Domain\SaleOrderRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
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
        $orders = PurchaseOrder::all();

        return view('purchaseOrders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = Product::all();
        $suppliers = Supplier::all();
        return view('purchaseOrders.create', compact('suppliers', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return string
     */
    public function store(Request $request)
    {
        $supplierId = $request->input('supplierId');
        $due = $request->input('due');

        $userId = Auth::user()->id;
        DB::beginTransaction();
        try {
            $purchaseOrder = new PurchaseOrder(['supplier_id' => $supplierId, 'due' => $due, 'user_id' => $userId]);
            $purchaseOrder->save();

            foreach ($request->input('products') as $product) {
                $purchaseOrder->purchaseOrderItems()->save(new PurchaseOrderItem(['product_item_code' => $product['item_code'],
                        'qty' => $product['qty'],
                        'cost' => $product['price']])
                );

                $selectedProduct = $this->productRepository->findById($product['item_code']);
                $selectedProduct->purchase($product['qty']);
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
