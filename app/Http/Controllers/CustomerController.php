<?php

namespace App\Http\Controllers;

use App\Data\Models\Customer;
use Illuminate\Http\Request;

//Todo: change the update & delete method to make Customer from the repository instead of factory
class CustomerController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = Customer::all();
        return view('customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $customer = new Customer();
        $customer->name = $request->input('name');
        $customer->email = $request->input('email');
        $customer->contact_no = $request->input('contact_no');
        $customer->address = $request->input('address');
        $customer->save();

        return redirect(url('/admin/customers'));
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
     * @param Customer $customer
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $customer = Customer::find($id);
        $customer->name = $request->input('name');
        $customer->email = $request->input('email');
        $customer->contact_no = $request->input('contact_no');
        $customer->address = $request->input('address');
        $customer->update();

        return redirect(url('/admin/customers'));
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
//            xdebug_break();
            Customer::find($id)->delete();
            return 'success';
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
