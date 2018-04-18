<?php
/**
 * Created by IntelliJ IDEA.
 * User: shamim
 * Date: 4/18/18
 * Time: 9:09 PM
 */

namespace App\Domain;


class Customer
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
    private $purchases;

    /**
     * Brand constructor.
     * @param $name
     */
    public function __construct($name,$due,$purchases)
    {
        $this->setName($name);
        $this->setDue($due);
        $this->setPurchases($purchases);
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
    public function getPurchases()
    {
        return $this->purchases;
    }

    /**
     * @param string $purchases
     */
    public function setPurchases($purchases)
    {
        Assert::notNull($purchases, 'purchases cannot be null');

        $this->purchases = $purchases;
    }

}