<?php


namespace App\Domain;


interface BrandRepository
{
    public function findById($id);

    public function save(Brand $brand);

    public function update(Brand $brand);

    public function delete(Brand $brand);

    public function getAll();
}
