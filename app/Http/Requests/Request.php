<?php

namespace App\Http\Requests;

use Illuminate\Http\Request as BaseRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;

class Request extends BaseRequest
{
    /**
     * @var array
     */
    protected $rules = [];

    /**
     * @var array
     */
    protected $messages = [];

    /**
     * @var array
     */
    protected $customAttributes = [];

    /**
     * @throws ValidationException
     */
    public function validate()
    {
        $validator = app('validator')
            ->make($this->all(), $this->rules, $this->messages, $this->customAttributes);

        if ($validator->fails()) {
            $this->throwValidationException($validator);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function throwValidationException($validator)
    {
        throw new ValidationException($validator, new JsonResponse($validator->errors()->getMessages(), 422));
    }
}