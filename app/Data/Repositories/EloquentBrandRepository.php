<?php


namespace App\Data\Repositories;


use App\Data\Mapper\EloquentBrandMapper;
use App\Data\Models\Brand as BrandDao;
use App\Domain\Brand;
use App\Domain\BrandRepository;
use App\Helper\Assert;

class EloquentBrandRepository implements BrandRepository
{

    /**
     * @var EloquentBrandMapper
     */
    private $eloquentBrandMapper;

    public function __construct(EloquentBrandMapper $mapper)
    {

        $this->eloquentBrandMapper = $mapper;
    }

    public function findById($id)
    {
        $brandModel = BrandDao::find($id);
        return $this->eloquentBrandMapper->toEntity($brandModel);
    }

    public function save(Brand $brand)
    {
        $eloquentBrand = $this->eloquentBrandMapper->fromEntity($brand);
        $eloquentBrand->save();
    }

    /**
     * @param Brand $brand
     * @return bool whether the update was successful or not
     */
    public function update(Brand $brand)
    {
        $brandDao = BrandDao::find($brand->getId());
        if ($brandDao) {
            $brandDao->name = $brand->getName();
            return $brandDao->save();
        }
        else
            return false;

    }

    public function delete(Brand $brand)
    {
        $brandDao = BrandDao::find($brand->getId());
        if (! $brandDao)
            return false;
        else {
            $brandDao->delete();
            return true;
        }
    }


    public function getAll()
    {
        return BrandDao::all();
    }
}
