<?php


namespace App\Data\Repositories;


use App\Data\Mapper\EloquentBrandMapper;
use App\Data\Mapper\EloquentCategoryMapper;
use App\Data\Mapper\EloquentProductMapper;
use App\Domain\Product;
use App\Domain\ProductRepository;

class EloquentProductRepository implements ProductRepository
{

    /**
     * @var EloquentProductMapper
     */
    private $productMapper;
    /**
     * @var EloquentBrandMapper
     */
    private $brandMapper;
    /**
     * @var EloquentCategoryMapper
     */
    private $categoryMapper;

    public function __construct(EloquentProductMapper $productMapper, EloquentBrandMapper $brandMapper, EloquentCategoryMapper $categoryMapper)
    {
        $this->productMapper = $productMapper;
        $this->brandMapper = $brandMapper;
        $this->categoryMapper = $categoryMapper;
    }

    public function findById($itemCode)
    {
        $productModel = \App\Data\Models\Product::find($itemCode);
        $brand = $this->brandMapper->toEntity($productModel->brand);
        $category = $this->categoryMapper->toEntity($productModel->category);
        return $this->productMapper->toEntity($productModel, $brand, $category);
    }

    public function save(Product $product)
    {
        $productDao = $this->productMapper->fromEntity($product);
        $productDao->save();
    }

    public function update(Product $product)
    {
        $productModel = $this->productMapper->fromEntity($product);
        $productModel->exists = true;

        $productModel->update();
    }

    public function delete(Product $product)
    {
        $productModel = $this->productMapper->fromEntity($product);
        $productModel->exists = true;

        $productModel->delete();
    }

    public function beginTransaction()
    {
        // TODO: Implement beginTransaction() method.
    }

    public function endTransaction()
    {
        // TODO: Implement endTransaction() method.
    }

    public function getAll()
    {
        return \App\Data\Models\Product::all();
    }
}
