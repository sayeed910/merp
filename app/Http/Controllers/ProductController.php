<?php

namespace App\Http\Controllers;

use App\Data\Models\Brand;
use App\Data\Models\Product;
use App\Domain\BrandRepository;
use App\Domain\CategoryRepository;
use App\Domain\EntityFactory;
use App\Domain\ProductRepository;
use App\Services\Validation\Validator;
use Assert\Assertion;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * @var Validator
     */
    private $validator;
    /**
     * @var ProductRepository
     */
    private $productRepository;
    /**
     * @var BrandRepository
     */
    private $brandRepository;
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;
    /**
     * @var EntityFactory
     */
    private $entityFactory;

    public function __construct(Validator $validator, ProductRepository $productRepository,
                                BrandRepository $brandRepository, CategoryRepository $categoryRepository,
                                EntityFactory $entityFactory)
    {
        $this->middleware('auth');
        $this->validator = $validator;
        $this->productRepository = $productRepository;
        $this->brandRepository = $brandRepository;
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
        $products = $this->productRepository->getAll();
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $brands = $this->brandRepository->getAll();
        $categories = $this->categoryRepository->getAll();
        return view('products.create', compact('brands', 'categories'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validateRequest($request);

        $brand = $this->brandRepository->findById($request->input('brand_id'));
        $category = $this->categoryRepository->findById($request->input('category_id'));

        $itemCode = $request->input('item_code');
        $name = $request->input('name');
        $unit = $request->input('unit');
        $size = $request->input('size');
        $salePrice = $request->input('price');
        $costPrice = $request->input('cost');


        $product = $this->entityFactory->productBuilder()
                                       ->setItemCode($itemCode)->setName($name)
                                       ->setBrand($brand)->setCategory($category)
                                       ->setUnit($unit)->setSize($size)
                                       ->setCostPrice($costPrice)->setSalePrice($salePrice)
                                       ->build();

        $this->productRepository->save($product);

        return redirect('admin/products');
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
     * @param $itemCode
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($itemCode)
    {
        $brands = $this->brandRepository->getAll();
        $categories = $this->categoryRepository->getAll();
        $product = Product::find($itemCode);
        return view('products.edit', compact('product', 'brands', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param $itemCode
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $itemCode)
    {
        $product = $this->productRepository->findById($itemCode);

        if ($request->has('name')) {
            $product->setName($request->input('name'));
        }
        if ($request->has('unit')) {
            $product->setUnit($request->input('unit'));
        }
        if ($request->has('size')) {
            $product->setSize($request->input('size'));
        }
        if ($request->has('cost')) {
            $product->setCostPrice($request->input('cost'));
        }
        if ($request->has('price')) {
            $product->setSalePrice($request->input('price'));
        }
        if ($request->has('brand_id')) {
            $brand_id = $request->input('brand_id');
            $brand = $this->brandRepository->findById($brand_id);
            $product->setBrand($brand);
        }
        if ($request->has('category_id')) {
            $category_id = $request->input('category_id');
            $category = $this->categoryRepository->findById($category_id);
            $product->setCategory($category);
        }

        $this->productRepository->update($product);

        return redirect('/admin/products');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $itemCode
     * @return \Illuminate\Http\Response
     */
    public function destroy($itemCode)
    {
        $product = $this->productRepository->findById($itemCode);
        $this->productRepository->delete($product);

        return response()->json(['success' => true]);

    }

    public function json(Product $product)
    {
        $product->brandName = $product->brand->name;
        return $product;
    }

    private function validateRequest($request)
    {
//        $this->validator->validate($request, [
//            'name' => 'required|regex:/^(a-zA-Z0-9 _){3,100}$'
//        ]);
    }
}
