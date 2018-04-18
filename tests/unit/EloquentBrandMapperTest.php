<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EloquentBrandMapperTest extends TestCase
{
    /**
    * @test
    *
    */
    public function it_should_map_from_entity(){
        $factory = resolve(\App\Domain\EntityFactory::class);
        $mapper = resolve(\App\Data\Mapper\EloquentBrandMapper::class);
        $brand = $factory->brand('abc', 1);
        $brandDao = $mapper->fromEntity($brand);

        self::assertEquals($brand->getName(), $brandDao->name);
        self::assertEquals($brand->getId(), $brandDao->id);
    }

}
