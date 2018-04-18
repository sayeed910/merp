<?php


namespace App\Domain;


interface ProductRepository extends Repository
{
    /**
     * @param $id
     * @return Product
     */
    public function findById($id);

    public function save(Product $product);

    public function update(Product $product);

    public function delete(Product $product);

    public function getAll();
}
