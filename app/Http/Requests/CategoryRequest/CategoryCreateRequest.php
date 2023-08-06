<?php

namespace App\Http\Requests\CategoryRequest;

use Illuminate\Foundation\Http\FormRequest;

class CategoryCreateRequest extends FormRequest
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

            'name_uz' => 'string|required|max:255',
            'name_ru' => 'string|required|max:255',
            'name_en' => 'string|required|max:255',
            'status' => 'integer|required',
            'type' => 'integer|required',
            'is_special' => 'integer|required',
            'sort' => 'integer|nullable',
        ];
    }
}
