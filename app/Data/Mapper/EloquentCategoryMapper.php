<?php


namespace App\Data\Mapper;


use App\Domain\Category;
use App\Data\Models\Category as CategoryDao;
use App\Domain\EntityFactory;

class EloquentCategoryMapper
{

    /**
     * @var EntityFactory
     */
    private $entityFactory;

    public function __construct(EntityFactory $entityFactory)
    {

        $this->entityFactory = $entityFactory;
    }

    public function fromEntity(Category $category){
        $categoryDao = new CategoryDao();
        $categoryDao->name = $category->getName();
        if ($category->getId()) {
            $categoryDao->id = $category->getId();

            //laravel checks this field to decide whether to update or save.
            //When the id value is set, this means the database already contains the record
            $categoryDao->exists = true;
        }
        return $categoryDao;
    }

    public function toEntity($categoryModel)
    {
        return $categoryModel == null ? null : $this->entityFactory->category($categoryModel->name, $categoryModel->id);
    }

}
