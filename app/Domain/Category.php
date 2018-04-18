<?php


namespace App\Domain;


use App\Helper\Assert;

class Category
{

    private $idWasChanged = false;

    /**
     * The id of the category in the database.
     * This field should be filled by db and as such is not in the constructor.
     * Once the field is set it will remain immutable throughout the life of the object.
     * @var string
     */
    private $id;
    private $name;

    /**
     * Category constructor.
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
            throw new \RuntimeException('Cannot change id of a category');
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
