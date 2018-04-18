<?php

namespace App\Domain;

use App\Helper\Assert;

class Brand
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

    /**
     * Brand constructor.
     * @param $name
     */
    public function __construct($name)
    {
        $this->setName($name);
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






}
