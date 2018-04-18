<?php


namespace App\Data\Repositories;


use App\Data\Mapper\EloquentCategoryMapper;
use App\Data\Models\Category as CategoryDao;
use App\Domain\Category;
use App\Domain\CategoryRepository;
use App\Helper\Assert;

class EloquentCategoryRepository implements CategoryRepository
{

    /**
     * @var EloquentCategoryMapper
     */
    private $eloquentCategoryMapper;

    public function __construct(EloquentCategoryMapper $eloquentCategoryMapper)
    {

        $this->eloquentCategoryMapper = $eloquentCategoryMapper;
    }

    public function findById($id)
    {
        $categoryModel = CategoryDao::find($id);
        return $this->eloquentCategoryMapper->toEntity($categoryModel);
    }

    public function save(Category $category)
    {
        $eloquentCategory = $this->eloquentCategoryMapper->fromEntity($category);
        $eloquentCategory->save();
    }

    /**
     * @param Category $category
     * @return bool whether the update was successful or not
     */
    public function update(Category $category)
    {
        $categoryDao = CategoryDao::find($category->getId());
        if ($categoryDao) {
            $categoryDao->name = $category->getName();
            return $categoryDao->save();
        }
        else
            return false;

    }

    public function delete(Category $category)
    {
        $categoryDao = CategoryDao::find($category->getId());
        if (! $categoryDao)
            return false;
        else {
            $categoryDao->delete();
            return true;
        }
    }


    public function getAll()
    {
        return CategoryDao::all();
    }
}
