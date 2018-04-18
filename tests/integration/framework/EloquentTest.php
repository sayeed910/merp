<?php

use App\Domain\Employee;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class ProductControllerTest
 *
 *
 */
class EloquentTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;
    use Codeception\Specify;


    public function testFind()
    {
        $this->specify('when record does not exist', function(){
           $this->specify('it should return null', function(){
               self::assertNull(\App\Data\Models\Brand::find(1));
           });
        });

    }


}
