<?php


namespace App\Services\Validation;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Validation\Factory as ValidatorFactory;


class Validator
{
//    /**
//     * @var ValidatorFactory
//     */
//    private $validator;
//
//    /**
//     * Validator constructor.
//     * @param ValidatorFactory $validator
//     */
//    public function __construct(ValidatorFactory $validator)
//    {
//        $this->validator = $validator;
//    }
//
//    public function validate(Request $request , array $rules, array $messages = [], array $customAttributes = [] ){
//        $this->validator->make($request->all(), $rules, $messages, $customAttributes);
//    }

    use ValidatesRequests;

}
