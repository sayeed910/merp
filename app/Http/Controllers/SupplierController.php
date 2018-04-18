<?php

namespace App\Http\Controllers;

use App\Domain\Supplier;
use App\Domain\SupplierRepository;
use App\Domain\EntityFactory;
use App\Services\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

//Todo: change the update & delete method to make Supplier from the repository instead of factory
class SupplierController extends Controller
{

    /**
     * @var SupplierRepository
     */
    private $supplierRepository;
    /**
     * @var EntityFactory
     */
    private $entityFactory;
    /**
     * @var Validator
     */
    private $validator;

    public function __construct(SupplierRepository $supplierRepository, EntityFactory $entityFactory, Validator $validator)
    {
        $this->middleware('auth');
        $this->supplierRepository = $supplierRepository;
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
        $suppliers = $this->supplierRepository->getAll();
        return view('suppliers.index', compact('suppliers'));
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
        $this->validateSupplierName($request);
        $name = $request->input('name');

        $supplier = new Supplier($name);
        $this->supplierRepository->save($supplier);


        return redirect('/admin/suppliers');
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
            $this->validateSupplierName($request);
            $name = $request->input('name');
            $supplier = $this->entityFactory->Supplier($name, $id);


            if ($this->supplierRepository->update($supplier))
                return response()->json(['success' => true]);
            else
                return response()->json(['success' => false, 'message' => 'The Supplier does not exist.']);
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
        $supplier = $this->entityFactory->Supplier('dummyname', $id);
        $wasSuccessful = $this->supplierRepository->delete($supplier);
        if ($wasSuccessful)
            return response()->json(['success' => true]);
        else
            return response()->json(['success' => false, 'message' => 'Supplier does not exist']);
    }

    /**
     * @param Request $request
     */
    public function validateSupplierName(Request $request)
    {
        $this->validator->validate($request, [
            'name' => 'required|unique:Suppliers|max:100|regex:/^([a-zA-Z0-9]+)$/'
        ], [
            'required' => 'The Supplier name can not be empty.',
            'unique' => 'A Supplier with this name already exists.',
            'regex' => 'The Supplier name must be one or more alphanumeric characters.'
        ]);
    }
}
