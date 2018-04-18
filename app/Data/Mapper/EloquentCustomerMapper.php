<?php


namespace App\Data\Mapper;


use App\Domain\Customer;
use App\Data\Models\Customer as CustomerDao;
use App\Domain\EntityFactory;

class EloquentCustomerMapper
{
    /**
     * @var EntityFactory
     */
    private $factory;

    /**
     * EloquentCustomerMapper constructor.
     * @param EntityFactory $factory
     */
    public function __construct(EntityFactory $factory)
    {
        $this->factory = $factory;
    }

    public function fromEntity(Customer $customer){
        $customerDao = new CustomerDao();
        $customerDao->name = $customer->getName();
        if ($customer->getId()) {
            $customerDao->id = $customer->getId();

            //eloquent checks this field to decide whether to update or save.
            //When the id value is set, this means the database already contains the record
            $customerDao->exists = true;
        }
        return $customerDao;
    }

    public function toEntity($customerModel)
    {
        return $this->factory->Customer($customerModel->name, $customerModel->id);
    }

}
