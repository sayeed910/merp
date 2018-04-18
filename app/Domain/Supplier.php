<?php
/**
 * Created by IntelliJ IDEA.
 * User: shamim
 * Date: 4/18/18
 * Time: 9:17 PM
 */

namespace App\Domain;


class Supplier
{
    private $idWasChanged = false;

    /**
     * The id of the brand in the database.
     * This field should be filled by db and as such is not in the constructor.
     * Once the field is set it will remain immutable throughout the life of the object.
     * @var string
     */
    private $id;
    private $name;
    private $due;
    private $sales;

    /**
     * Brand constructor.
     * @param $name
     */
    public function __construct($name,$due,$sales)
    {
        $this->setName($name);
        $this->setDue($due);
        $this->setsales($sales);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        Assert::notNull($id, 'id can not be null');
        if (! $this->idWasChanged)
            $this->idWasChanged = true;
        else
            throw new \RuntimeException('Trying to change the id of brand which is immutable');
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        Assert::notNull($name, 'name cannot be null');

        $this->name = $name;
    }
    public function getDue()
    {
        return $this->due;
    }

    /**
     * @param string $name
     */
    public function setDue($due)
    {
        Assert::notNull($due, 'due cannot be null');

        $this->due = $due;
    }
    public function getSales()
    {
        return $this->sales;
    }

    /**
     * @param string $sales
     */
    public function setSales($sales)
    {
        Assert::notNull($sales, 'sales cannot be null');

        $this->sales = $sales;
    }
}