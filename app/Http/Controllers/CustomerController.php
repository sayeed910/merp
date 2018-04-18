<?php

namespace App\Http\Controllers;

use App\Domain\Customer;
use App\Domain\CustomerRepository;
use App\Domain\EntityFactory;
use App\Services\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

//Todo: change the update & delete method to make Customer from the repository instead of factory
class CustomerController extends Controller
{

    /**
     * @var CustomerRepository
     */
    private $customerRepository;
    /**
     * @var EntityFactory
     */
    private $entityFactory;
    /**
     * @var Validator
     */
    private $validator;

    public function __construct(CustomerRepository $customerRepository, EntityFactory $entityFactory, Validator $validator)
    {
        $this->middleware('auth');
        $this->customerRepository = $customerRepository;
        $this->entityFactory = $entityFactory;
        $this->validator = $validator;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = $this->CustomerRepository->getAll();
        return view('customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validateCustomerName($request);
        $name = $request->input('name');

        $customer = new Customer($name);
        $this->customerRepository->save($customer);


        return redirect('/admin/customers');
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
     * @param string $id
     * @return Response
     */
    public function update(Request $request, $id)
    {

        try {
            $this->validateCustomerName($request);
            $name = $request->input('name');
            $customer = $this->entityFactory->Customer($name, $id);


            if ($this->customerRepository->update($customer))
                return response()->json(['success' => true]);
            else
                return response()->json(['success' => false, 'message' => 'The Customer does not exist.']);
        } catch (ValidationException $ex){
            return response()->json(['success' => false, 'message' => $ex->validator->errors()->first()]);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        $customer = $this->entityFactory->Customer('dummyname', $id);
        $wasSuccessful = $this->customerRepository->delete($customer);
        if ($wasSuccessful)
            return response()->json(['success' => true]);
        else
            return response()->json(['success' => false, 'message' => 'Customer does not exist']);
    }

    /**
     * @param Request $request
     */
    public function validateCustomerName(Request $request)
    {
        $this->validator->validate($request, [
            'name' => 'required|unique:Customers|max:100|regex:/^([a-zA-Z0-9]+)$/'
        ], [
            'required' => 'The Customer name can not be empty.',
            'unique' => 'A Customer with this name already exists.',
            'regex' => 'The Customer name must be one or more alphanumeric characters.'
        ]);
    }
}
