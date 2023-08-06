<?php

namespace App\Http\Requests\CategoryRequest;

use Illuminate\Foundation\Http\FormRequest;

class CategoryUpdateRequest extends FormRequest
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
            'status' => 'integer|nullable',
            'type' => 'integer|nullable',
            'is_special' => 'integer|nullable',
            'sort' => 'integer|nullable',
        ];
    }
}
