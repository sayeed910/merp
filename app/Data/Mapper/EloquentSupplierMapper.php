<?php


namespace App\Data\Mapper;


use App\Domain\Supplier;
use App\Data\Models\Supplier as SupplierDao;
use App\Domain\EntityFactory;

class EloquentSupplierMapper
{
    /**
     * @var EntityFactory
     */
    private $factory;

    /**
     * EloquentSupplierMapper constructor.
     * @param EntityFactory $factory
     */
    public function __construct(EntityFactory $factory)
    {
        $this->factory = $factory;
    }

    public function fromEntity(Supplier $supplier){
        $supplierDao = new SupplierDao();
        $supplierDao->name = $supplier->getName();
        if ($supplier->getId()) {
            $supplierDao->id = $supplier->getId();

            //eloquent checks this field to decide whether to update or save.
            //When the id value is set, this means the database already contains the record
            $supplierDao->exists = true;
        }
        return $supplierDao;
    }

    public function toEntity($supplierModel)
    {
        return $this->factory->Supplier($supplierModel->name, $supplierModel->id);
    }

}
