<?php

namespace App\Http\Requests;

use App\Http\Requests\BaseFormRequest;

class UploadImageRequest extends BaseFormRequest
{
//    /**
//     * Determine if the user is authorized to make this request.
//     *
//     * @return bool
//     */
//    public function authorize(): bool
//    {
//        return true;
//    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'file' => 'required|image|mimes:jpg,png|max:30720',
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
            //
        ];
    }
}
