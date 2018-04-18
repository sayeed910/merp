<?php

use App\Data\Models\Category as CategoryDao;
use App\Data\Repositories\EloquentCategoryRepository;
use App\Domain\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class CategoryTest
 *
 */
class CategoryTest extends TestCase
{
    use DatabaseTransactions;
    use DatabaseMigrations;


    //When he visits /admin/categories

    /**
     * @test
     * Given he is unauthenticated
     */
    public function it_should_redirect_unauthenticated_user_to_login_page()
    {
        $this->visit('/admin/categories')
             ->seePageIs('/login');

    }

    /**
     * @test
     * authenticated user will be shown a page with category list
     */
    public function it_should_show_a_list_of_categories()
    {
        $category = new Category('brind');
        $categoryRepository = resolve(\App\Domain\CategoryRepository::class);
        $categoryRepository->save($category);

        $this->authenticate()->visit('/admin/categories')->see($category->getName());
    }


    /**
     * @test
     * When he does a post request to /admin/categories with appropriate data
     *
     */
    public function it_should_create_new_category()
    {
        $this->authenticate()->post('/admin/categories', ['name' => 'brind', '_token' => csrf_token()]);
        CategoryDao::where('name', 'brind')->firstOrFail();
    }


    //When he does a patch request to /admin/categories/id

    /**
     * @test
     * Given the requested name for the category is valid and the category exists
     */
    public function it_should_update_the_category()
    {
        $category = factory(CategoryDao::class)->create();
        $this->authenticate()
             ->patch('/admin/categories/' . $category->id, ['name' => 'abcde', '_token' => csrf_token()]);

        self::assertEquals('abcde', CategoryDao::find($category->id)->name);

        $this->assertRedirectedTo("/admin/categories");
        $this->visit('/admin/categories')->see("abcde");


    }

    /**
     * @test
     * Given the category does not exist
     */
    public function it_should_respond_about_the_absence_of_category()
    {
        $this->authenticate()->patch('/admin/categories/' . 7, ['name' => 'abcde', '_token' => csrf_token()]);

        $expectedResponse = json_encode(['success' => false, 'message' => 'The category does not exist.']);
        self::assertEquals($expectedResponse, $this->response->content());

    }

    /**
     * @test
     * Given the name is not unique
     */
    public function it_should_respond_that_category_already_exists()
    {
        $category1 = factory(CategoryDao::class)->create();
        $category2 = factory(CategoryDao::class)->create();
        $this->authenticate()
             ->patch('/admin/categories/' . $category1->id, ['name' => $category2->name, '_token' => csrf_token()]);

        $expectedNonUniqueResponse = json_encode(['success' => false, 'message' => 'A category with this name already exists.']);
        self::assertEquals($expectedNonUniqueResponse, $this->response->content());

    }

    /**
     * @test
     * Given the name is null or empty
     */
    public function it_should_respond_that_category_cannot_be_null_empty()
    {

        $this->authenticate()->patch('/admin/categories/' . 1, ['_token' => csrf_token()]);
        $expectedNonUniqueResponse = json_encode(['success' => false, 'message' => 'The category name can not be empty.']);

        self::assertEquals($expectedNonUniqueResponse, $this->response->content());


        $this->patch('/admin/categories/' . 1, ['name' => '', '_token' => csrf_token()]);

        self::assertEquals($expectedNonUniqueResponse, $this->response->content());


        $this->patch('/admin/categories/' . 1, ['name' => 'abc<html>ed', '_token' => csrf_token()]);
        $invalidCharacterResponse = json_encode(['success' => false, 'message' => 'The category name must be one or more alphanumeric characters.']);

        self::assertEquals($invalidCharacterResponse, $this->response->content());


    }


    //When he does a delete request to /admin/categories/id

    /**
     * @test
     * Given the category exists
     */
    public function it_should_be_deleted()
    {
        $category = factory(CategoryDao::class)->create();
        $this->authenticate()->delete('/admin/categories/' . $category->id);

        self::assertFalse(CategoryDao::where('id', $category->id)->exists());

        self::assertEquals(json_encode(['success' => true]), $this->response->content());
    }

    /**
     * @test
     * Given the category does not exist
     */
    public function it_should_respond_that_category_does_not_exist()
    {
        $this->authenticate()->delete('/admin/categories/' . 1);

        self::assertEquals(json_encode(['success' => false, 'message' => 'Category does not exist']), $this->response->content());
    }


}
