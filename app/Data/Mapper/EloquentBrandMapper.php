<?php


namespace App\Data\Mapper;


use App\Domain\Brand;
use App\Data\Models\Brand as BrandDao;
use App\Domain\EntityFactory;

class EloquentBrandMapper
{
    /**
     * @var EntityFactory
     */
    private $factory;

    /**
     * EloquentBrandMapper constructor.
     * @param EntityFactory $factory
     */
    public function __construct(EntityFactory $factory)
    {
        $this->factory = $factory;
    }

    public function fromEntity(Brand $brand){
        $brandDao = new BrandDao();
        $brandDao->name = $brand->getName();
        if ($brand->getId()) {
            $brandDao->id = $brand->getId();

            //eloquent checks this field to decide whether to update or save.
            //When the id value is set, this means the database already contains the record
            $brandDao->exists = true;
        }
        return $brandDao;
    }

    public function toEntity($brandModel)
    {
        return $this->factory->brand($brandModel->name, $brandModel->id);
    }

}
