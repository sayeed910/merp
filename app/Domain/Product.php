<?php

namespace App\Domain;


class Product
{
    protected $itemCode;
    protected $name;
    protected $brand;
    protected $category;
    protected $unit;
    protected $size;
    protected $costPrice;
    protected $salePrice;
    protected $damaged;
    protected $stock;
    protected $returned;
    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * Product constructor. This is for testing purpose. Do not use it to create Product.
     * Use <code>EntityFactory#product()</code> instead.
     * @param string $itemCode
     * @param string $name
     * @param $unit
     * @param $size
     * @param Brand $brand
     * @param Category $category
     * @param int $costPrice
     * @param int $salePrice
     * @param ProductRepository $productRepository
     * @param int $damaged
     * @param int $returned
     * @param int $stock
     */
    public function __construct($itemCode, $name, $unit, $size, Brand $brand, Category $category,
                                $costPrice, $salePrice, ProductRepository $productRepository,
                                $damaged = 0, $returned = 0, $stock = 0)
    {
        $this->itemCode = $itemCode;
        $this->name = $name;
        $this->unit = $unit;
        $this->size = $size;
        $this->brand = $brand;
        $this->category = $category;
        $this->costPrice = $costPrice;
        $this->salePrice = $salePrice;
        $this->productRepository = $productRepository;

        $this->damaged = $damaged;
        $this->returned = $returned;
        $this->stock = $stock;
    }


    public function purchase($qty)
    {
        if ($this->stock - $qty < 0) {
            throw new \RuntimeException("Product not available");
        } else {
            $this->stock += $qty;
            $this->productRepository->update($this);
        }
    }


    /**
     * @return string
     */
    public function getItemCode()
    {
        return $this->itemCode;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return Brand
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @return int
     */
    public function getCostPrice()
    {
        return $this->costPrice;
    }

    /**
     * @return int
     */
    public function getSalePrice()
    {
        return $this->salePrice;
    }

    /**
     * @return int
     */
    public function getDamaged()
    {
        return $this->damaged;
    }

    /**
     * @return int
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * @return int
     */
    public function getReturned()
    {
        return $this->returned;
    }

    /**
     * @return mixed
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param Brand $brand
     */
    public function setBrand($brand)
    {
        $this->brand = $brand;
    }

    /**
     * @param Category $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @param mixed $unit
     */
    public function setUnit($unit)
    {
        $this->unit = $unit;
    }

    /**
     * @param mixed $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * @param int $costPrice
     */
    public function setCostPrice($costPrice)
    {
        $this->costPrice = $costPrice;
    }

    /**
     * @param int $salePrice
     */
    public function setSalePrice($salePrice)
    {
        $this->salePrice = $salePrice;
    }


}
