<?php
/**
 * Created by IntelliJ IDEA.
 * User: sayeed
 * Date: 3/19/18
 * Time: 9:10 PM
 */

namespace App\Domain;


interface CategoryRepository
{
    public function findById($id);

    public function save(Category $category);

    public function update(Category $category);

    public function delete(Category $category);

    public function getAll();
}
