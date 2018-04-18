<?php


namespace App\Domain;


interface CustomerRepository extends Repository
{
    /**
     * @param $id
     * @return Customer
     */
    public function findById($id);

    public function save(Product $customer);

    public function update(Product $customer);

    public function delete(Product $customer);

    public function getAll();
}
