<?php

namespace App\Http\Controllers;

use App\Domain\Category;
use App\Domain\CategoryRepository;
use App\Domain\EntityFactory;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
//Todo: change the update & delete method to make category from the repository instead of factory

class CategoryController extends Controller
{
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;
    /**
     * @var EntityFactory
     */
    private $entityFactory;

    public function __construct(CategoryRepository $categoryRepository, EntityFactory $entityFactory)
    {
        $this->middleware('auth');
        $this->categoryRepository = $categoryRepository;
        $this->entityFactory = $entityFactory;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = $this->categoryRepository->getAll();
        return view('categories.index', compact('categories'));
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
        $this->validateCategoryName($request);
        $name = $request->input('name');

        $category = $this->entityFactory->category($name);
        $this->categoryRepository->save($category);


        return redirect('/admin/categories');
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
            return $this->validateAndUpdate($request, $id);
        } catch (ValidationException $ex){
            return response()->json(['success' => false, 'message' => $ex->validator->errors()->first()]);
        }




    }

    private function validateAndUpdate(Request $request, $id)
    {
        $this->validateCategoryName($request);
        $name = $request->input('name');

        $category = $this->categoryRepository->findById($id);

        if ($category)
           return $this->updateCategory($category, $name);
        else
            return response()->json(['success' => false, 'message' => 'The category does not exist.']);




    }

    private function updateCategory(Category $category, $name){
        $category->setName($name);
        if ($this->categoryRepository->update($category))
            return redirect('/admin/categories');
        else return response()->json(['success' => false, 'message' => 'Could not update category name']);
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
        $category = $this->entityFactory->category('dummyname', $id);
        $wasSuccessful = $this->categoryRepository->delete($category);
        if ($wasSuccessful)
            return response()->json(['success' => true]);
        else
            return response()->json(['success' => false, 'message' => 'Category does not exist']);
    }

    /**
     * @param Request $request
     */
    public function validateCategoryName(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:categories|max:100|regex:/^([a-zA-Z_ \-0-9]+)$/'
        ], [
            'required' => 'The category name can not be empty.',
            'unique' => 'A category with this name already exists.',
            'regex' => 'The category name must be one or more alphanumeric characters.'
        ]);
    }
}
