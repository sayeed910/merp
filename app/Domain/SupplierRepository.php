<?php


namespace App\Domain;


interface SupplierRepository extends Repository
{
    /**
     * @param $id
     * @return supplier
     */
    public function findById($id);

    public function save(supplier $supplier);

    public function update(supplier $supplier);

    public function delete(supplier $supplier);

    public function getAll();
}
