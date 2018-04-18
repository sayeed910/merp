<?php
/**
 * Created by IntelliJ IDEA.
 * User: sayeed
 * Date: 3/27/18
 * Time: 6:16 PM
 */

namespace App\Domain;


interface Repository
{
    public function beginTransaction();
    public function endTransaction();

}
