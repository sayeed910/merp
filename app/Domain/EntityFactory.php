<?php


namespace App\Domain;


use App\Helper\Assert;

class EntityFactory
{
    private $employeeRepository = null;
    private $productRepository = null;


    public function employee($name, $designation, $salary, $contactNo)
    {

        if (! $this->employeeRepository)
            $this->employeeRepository = resolve(EmployeeRepository::class);

        return new Employee($this->employeeRepository, $name, $designation, $salary, $contactNo);
    }


    public function productBuilder()
    {
        if (!$this->productRepository)
            $this->productRepository = resolve(ProductRepository::class);

        return new ProductBuilder($this->productRepository);
    }

    public function brand($name, $id = null)
    {
        $brand = new Brand($name);
        if ($id) $brand->setId($id);
        return $brand;
    }

    public function category($name, $id = null)
    {
        $category = new Category($name);
        if ($id) $category->setId($id);
        return $category;
    }
    public function customer($name, $id = null,$due,$purchases)
    {

        $customer = new Customer($name);
        if ($id) $customer->setId($id);
        if ($due) $customer->setDue($due);
        if ($purchases) $customer->setPurchases($purchases);
        return $customer;
    }
    public function supplier($name, $id = null,$due,$sales)
    {

        $supplier = new Supplier($name);
        if ($id) $supplier->setId($id);
        if ($due) $supplier->setDue($due);
        if ($sales) $supplier->setPurchases($sales);
        return $supplier;
    }
    public function purchaseOrder($supplier, $user)
    {
        return new PurchaseOrder($supplier, $user);
    }

    public function orderItem($item, $qty)
    {
        return new PurchaseOrderItem($item, $qty);
    }
}


class ProductBuilder {
    protected $itemCode;
    protected $name;
    protected $brand;
    protected $category;
    protected $costPrice;
    protected $salePrice;
    protected $unit;
    protected $size;

    protected $damaged;
    protected $stock;
    protected $returned;

    protected $productRepository;

    /**
     * ProductBuilder constructor.
     * @param $productRepository
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;

        $this->damaged = $this->returned = $this->stock = 0;
    }


    /**
     * @param mixed $itemCode
     */
    public function setItemCode($itemCode)
    {
        $this->itemCode = $itemCode;

        return $this;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param mixed $brand
     */
    public function setBrand($brand)
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @param mixed $costPrice
     */
    public function setCostPrice($costPrice)
    {
        $this->costPrice = $costPrice;

        return $this;
    }

    /**
     * @param mixed $salePrice
     * @return ProductBuilder
     */
    public function setSalePrice($salePrice)
    {
        $this->salePrice = $salePrice;

        return $this;
    }

    /**
     * @param mixed $unit
     */
    public function setUnit($unit)
    {
        $this->unit = $unit;
        return $this;
    }

    /**
     * @param mixed $size
     * @return ProductBuilder
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @param mixed $damaged
     * @return ProductBuilder
     */
    public function setDamaged($damaged)
    {
        $this->damaged = $damaged;
        return $this;
    }

    /**
     * @param mixed $stock
     * @return ProductBuilder
     */
    public function setStock($stock)
    {
        $this->stock = $stock;
        return $this;
    }

    /**
     * @param mixed $returned
     * @return ProductBuilder
     */
    public function setReturned($returned)
    {
        $this->returned = $returned;
        return $this;
    }


    public function build(){
        //TODO: validation
        return new Product($this->itemCode, $this->name, $this->unit, $this->size, $this->brand, $this->category,
            $this->costPrice, $this->salePrice, $this->productRepository, $this->damaged, $this->returned, $this->stock);
    }


}

