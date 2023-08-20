<?php

namespace App\Http\Requests;

use App\Exceptions\CustomValidationException;
use Cassandra\Exception\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;


abstract class BaseFormRequest extends FormRequest
{
    /**
     * @return array
     */
    abstract public function errorCode(): array;

    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     *
     * @return void
     * @throws ValidationException|CustomValidationException
     */
    protected function failedValidation(Validator $validator): void
    {
        throw (new CustomValidationException($this->errorCode(), $validator));
    }
}
