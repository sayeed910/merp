<?php

use App\Data\Models\Brand as BrandDao;
use App\Data\Models\User;
use App\Data\Repositories\EloquentBrandRepository;
use App\Domain\Brand;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class ProductControllerTest
 * Given Mr. Rafat is a user
 * When he visits the products page
 *
 */
class BrandListTest extends TestCase
{
    use DatabaseTransactions;
    use DatabaseMigrations;

    /**
     * @test
     * he will be redirected to login page if he is not authenticated
     */
    public function it_should_redirect_unauthenticated_user_to_login_page()
    {
        $this->visit('/admin/brands')
            ->seePageIs('/login');

    }

    /**
     * @test
     * authenticated user will be shown a page with brand list
     */
    public function it_should_show_a_list_of_brands()
    {
        $brand = new Brand('brind');
        $brandRepository = resolve(\App\Domain\BrandRepository::class);
        $brandRepository->save($brand);

        $this->authenticate()->visit('/admin/brands')->see($brand->getName());
    }


    /**
     * @test
     * When he does a post request to /admin/brands with appropriate data
     *
     */
    public function it_should_create_new_brand()
    {
        $this->authenticate()->post('/admin/brands', ['name' => 'brind', '_token' => csrf_token()]);
        BrandDao::where('name', 'brind')->firstOrFail();
    }


    //When he does a patch request to /admin/brands/id

    /**
     * @test
     * Given the requested name for the brand is valid and the brand exists
     */
    public function it_should_update_the_brand()
    {
        $brand = factory(BrandDao::class)->create();
        $this->authenticate()->patch('/admin/brands/' . $brand->id, ['name' => 'abcde', '_token' => csrf_token()]);

        self::assertEquals(json_encode(['success' => true]), $this->response->content());
        self::assertEquals('abcde', BrandDao::find($brand->id)->name);


    }

    /**
     * @test
     * Given the brand does not exist
     */
    public function it_should_respond_about_the_absence_of_brand()
    {
        $this->authenticate()->patch('/admin/brands/' . 7, ['name' => 'abcde', '_token' => csrf_token()]);

        $expectedResponse = json_encode(['success' => false, 'message' => 'The brand does not exist.']);
        self::assertEquals($expectedResponse, $this->response->content());

    }

    /**
     * @test
     * Given the name is not unique
     */
    public function it_should_respond_that_brand_already_exists()
    {
        $brand1 = factory(BrandDao::class)->create();
        $brand2 = factory(BrandDao::class)->create();
        $this->authenticate()->patch('/admin/brands/' . $brand1->id, ['name' => $brand2->name, '_token' => csrf_token()]);

        $expectedNonUniqueResponse = json_encode(['success' => false, 'message' => 'A brand with this name already exists.']);
        self::assertEquals($expectedNonUniqueResponse, $this->response->content());

    }

    /**
     * @test
     * Given the name is null or empty
     */
    public function it_should_respond_that_brand_cannot_be_null_empty()
    {

        $this->authenticate()->patch('/admin/brands/' . 1, ['_token' => csrf_token()]);
        $expectedNonUniqueResponse = json_encode(['success' => false, 'message' => 'The brand name can not be empty.']);

        self::assertEquals($expectedNonUniqueResponse, $this->response->content());


        $this->patch('/admin/brands/' . 1, ['name' => '', '_token' => csrf_token()]);

        self::assertEquals($expectedNonUniqueResponse, $this->response->content());


        $this->patch('/admin/brands/' . 1, ['name' => 'abc<html>ed', '_token' => csrf_token()]);
        $invalidCharacterResponse = json_encode(['success' => false, 'message' => 'The brand name must be one or more alphanumeric characters.']);

        self::assertEquals($invalidCharacterResponse, $this->response->content());





    }


    //When he does a delete request to /admin/brands/id

    /**
     * @test
     * Given the brand exists
     */
    public function it_should_be_deleted()
    {
        $brand = factory(BrandDao::class)->create();
        $this->authenticate()->delete('/admin/brands/'.$brand->id);

        self::assertFalse(BrandDao::where('id', $brand->id)->exists());

        self::assertEquals(json_encode(['success' => true]), $this->response->content());
    }

    /**
    * @test
    * Given the brand does not exist
    */
    public function it_should_respond_that_brand_does_not_exist(){
        $this->authenticate()->delete('/admin/brands/'. 1);

        self::assertEquals(json_encode(['success' => false, 'message' => 'Brand does not exist']), $this->response->content());
    }



}
