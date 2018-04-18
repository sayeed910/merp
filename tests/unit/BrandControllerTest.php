<?php

use App\Domain\Brand;
use App\Http\Controllers\BrandController;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BrandControllerTest extends TestCase
{
    use DatabaseTransactions;
    use DatabaseMigrations;
    /**
    * @test
    *
    */
    public function it_should_call_update_on_repository(){
       $brandRepo = $this->createMock(\App\Domain\BrandRepository::class);
       $factory = $this->createMock(\App\Domain\EntityFactory::class);
       $validator = $this->createMock(\App\Services\Validation\Validator::class);
       $request = $this->createMock(\Illuminate\Http\Request::class);

       $factory->method('brand')->willReturn(new Brand("dummy"));

       $controller = new BrandController($brandRepo, $factory, $validator);

       $brandRepo->expects($this->once())->method('update')->withAnyParameters();
       $controller->update($request, 1);


    }

}
