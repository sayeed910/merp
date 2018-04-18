<?php


namespace App\Data\Repositories;


use App\Data\Mapper\EloquentCustomerMapper;
use App\Data\Models\Customer as CustomerDao;
use App\Domain\Customer;
use App\Domain\CustomerRepository;
use App\Helper\Assert;

class EloquentCustomerRepository implements CustomerRepository
{

    /**
     * @var EloquentCustomerMapper
     */
    private $eloquentCustomerMapper;

    public function __construct(EloquentCustomerMapper $mapper)
    {

        $this->eloquentCustomerMapper = $mapper;
    }

    public function findById($id)
    {
        $customerModel = CustomerDao::find($id);
        return $this->eloquentCustomerMapper->toEntity($customerModel);
    }

    public function save(Customer $customer)
    {
        $eloquentCustomer = $this->eloquentCustomerMapper->fromEntity($customer);
        $eloquentCustomer->save();
    }

    /**
     * @param Customer $customer
     * @return bool whether the update was successful or not
     */
    public function update(Customer $customer)
    {
        $customerDao = CustomerDao::find($customer->getId());
        if ($customerDao) {
            $customerDao->name = $customer->getName();
            return $customerDao->save();
        }
        else
            return false;

    }

    public function delete(Customer $customer)
    {
        $customerDao = CustomerDao::find($customer->getId());
        if (! $customerDao)
            return false;
        else {
            $customerDao->delete();
            return true;
        }
    }


    public function getAll()
    {
        return CustomerDao::all();
    }
}
