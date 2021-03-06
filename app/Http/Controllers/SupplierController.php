<?php

namespace App\Http\Controllers;

use App\Data\Models\PurchaseOrder;
use App\Data\Models\Supplier;
use Illuminate\Http\Request;

//Todo: change the update & delete method to make Supplier from the repository instead of factory
class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $suppliers = Supplier::all();
        return view('suppliers.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('suppliers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $supplier = new Supplier();
        $supplier->name = $request->input('name');
        $supplier->email = $request->input('email');
        $supplier->contact_no = $request->input('contact_no');
        $supplier->address = $request->input('address');
        $supplier->save();

        return redirect(url('/admin/suppliers'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $customer = Supplier::find($id);
        $orders = PurchaseOrder::where('supplier_id', $id)->get();
        return view('suppliers.show', compact('customer', 'orders'));
    }

    public function purchase(Request $request, $id)
    {
        $year = $request->input('year');
        $customer = Supplier::find($id);
        return $customer->purchaseInYear($year);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $customer = Supplier::find($id);
        return view('suppliers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Supplier $supplier
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $supplier = Supplier::find($id);
        $supplier->name = $request->input('name');
        $supplier->email = $request->input('email');
        $supplier->contact_no = $request->input('contact_no');
        $supplier->address = $request->input('address');
        $supplier->update();

        return redirect('/admin/suppliers');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return string
     */
    public function destroy(Request $request, $id)
    {
        try {

            Supplier::find($id)->delete();
            return 'success';
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
