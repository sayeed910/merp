<?php

namespace App\Http\Controllers;

use App\Domain\Brand;
use App\Domain\BrandRepository;
use App\Domain\EntityFactory;
use App\Services\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

//Todo: change the update & delete method to make brand from the repository instead of factory
class BrandController extends Controller
{

    /**
     * @var BrandRepository
     */
    private $brandRepository;
    /**
     * @var EntityFactory
     */
    private $entityFactory;
    /**
     * @var Validator
     */
    private $validator;

    public function __construct(BrandRepository $brandRepository, EntityFactory $entityFactory, Validator $validator)
    {
//        $this->middleware('auth');
        $this->brandRepository = $brandRepository;
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
        $brands = $this->brandRepository->getAll();
        return view('brands.index', compact('brands'));
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
        $this->validateBrandName($request);
        $name = $request->input('name');

        $brand = new Brand($name);
        $this->brandRepository->save($brand);


        return redirect('/admin/brands');
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
            $this->validateBrandName($request);
            $name = $request->input('name');
            $brand = $this->entityFactory->brand($name, $id);


            if ($this->brandRepository->update($brand))
                return response()->json(['success' => true]);
            else
                return response()->json(['success' => false, 'message' => 'The brand does not exist.']);
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
            $brand = $this->entityFactory->brand('dummyname', $id);
            $wasSuccessful = $this->brandRepository->delete($brand);
            if ($wasSuccessful)
                return response()->json(['success' => true]);
            else
                return response()->json(['success' => false, 'message' => 'Brand does not exist']);
    }

    /**
     * @param Request $request
     */
    public function validateBrandName(Request $request)
    {
        $this->validator->validate($request, [
            'name' => 'required|unique:brands|max:100|regex:/^([a-zA-Z0-9]+)$/'
        ], [
            'required' => 'The brand name can not be empty.',
            'unique' => 'A brand with this name already exists.',
            'regex' => 'The brand name must be one or more alphanumeric characters.'
        ]);
    }
}
