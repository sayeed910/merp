<?php


namespace App\Data\Repositories;


use App\Data\Mapper\EloquentSupplierMapper;
use App\Data\Models\Supplier as SupplierDao;
use App\Domain\Supplier;
use App\Domain\SupplierRepository;
use App\Helper\Assert;

class EloquentSupplierRepository implements SupplierRepository
{

    /**
     * @var EloquentSupplierMapper
     */
    private $eloquentSupplierMapper;

    public function __construct(EloquentSupplierMapper $mapper)
    {

        $this->eloquentSupplierMapper = $mapper;
    }

    public function findById($id)
    {
        $supplierModel = SupplierDao::find($id);
        return $this->eloquentSupplierMapper->toEntity($supplierModel);
    }

    public function save(Supplier $supplier)
    {
        $eloquentSupplier = $this->eloquentSupplierMapper->fromEntity($supplier);
        $eloquentSupplier->save();
    }

    /**
     * @param Supplier $supplier
     * @return bool whether the update was successful or not
     */
    public function update(Supplier $supplier)
    {
        $supplierDao = SupplierDao::find($supplier->getId());
        if ($supplierDao) {
            $supplierDao->name = $supplier->getName();
            return $supplierDao->save();
        }
        else
            return false;

    }

    public function delete(Supplier $supplier)
    {
        $supplierDao = SupplierDao::find($supplier->getId());
        if (! $supplierDao)
            return false;
        else {
            $supplierDao->delete();
            return true;
        }
    }


    public function getAll()
    {
        return SupplierDao::all();
    }
}
