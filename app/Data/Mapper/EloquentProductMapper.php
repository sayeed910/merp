<?php


namespace App\Data\Mapper;


use App\Domain\Category;
use App\Domain\Brand;
use App\Data\Models\Product as ProductDao;
use App\Domain\EntityFactory;
use App\Domain\Product;

class EloquentProductMapper
{
    /**
     * @var EntityFactory
     */
    private $factory;

    /**
     * EloquentProductMapper constructor.
     * @param EntityFactory $factory
     */
    public function __construct(EntityFactory $factory)
    {

        $this->factory = $factory;
    }

    public function fromEntity(Product $product)
    {
        $productDao = new ProductDao();
        $productDao->item_code = $product->getItemCode();
        $productDao->name = $product->getName();
        $productDao->size = $product->getSize();
        $productDao->unit = $product->getUnit();
        $productDao->brand_id = $product->getBrand()->getId();
        $productDao->category_id = $product->getCategory()->getId();
        $productDao->cost = $product->getCostPrice();
        $productDao->price = $product->getSalePrice();
        $productDao->damaged = $product->getDamaged();
        $productDao->stock = $product->getStock();
        $productDao->returned = $product->getReturned();

        return $productDao;
    }

    public function toEntity(\App\Data\Models\Product $productModel, Brand $brand, Category $category)
    {
        return $this->factory->productBuilder()
                             ->setItemCode($productModel->item_code)->setName($productModel->name)
                             ->setBrand($brand)->setCategory($category)
                             ->setCostPrice($productModel->cost)->setSalePrice($productModel->price)
                             ->setUnit($productModel->unit)->setSize($productModel->size)
                             ->setStock($productModel->stock)->setDamaged($productModel->damaged)
                             ->setReturned($productModel->returned)
                             ->build();
    }

}
