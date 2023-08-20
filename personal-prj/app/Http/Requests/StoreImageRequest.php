<?php

namespace App\Http\Requests;

class StoreImageRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'file' => 'required|image|mimes:png,jpeg|max:5120',
        ];
    }

    /**
     * Defined error id of validation
     *
     * @return string[]
     */
    public function errorCode(): array
    {
        return [
            'file.required' => config('file.error-code.file.required'),
            'file.image' => config('file.error-code.file.image'),
            'file.mimes' => config('file.error-code.file.mimes'),
            'file.max' => config('file.error-code.file.max'),
        ];
    }
}
