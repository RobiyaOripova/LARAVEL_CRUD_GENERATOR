<?php

namespace App\Http\Requests\CountryRequest;

use Illuminate\Foundation\Http\FormRequest;

class CountryCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [

            'name_uz' => 'string|nullable|max:255',
            'name_ru' => 'string|nullable|max:255',
            'name_en' => 'string|nullable|max:255',
            'code' => 'string|required|max:255',
            'status' => 'integer|required',
        ];
    }
}
