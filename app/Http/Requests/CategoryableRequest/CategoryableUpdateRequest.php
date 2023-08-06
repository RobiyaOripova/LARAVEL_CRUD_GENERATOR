<?php

namespace App\Http\Requests\CategoryableRequest;

use Illuminate\Foundation\Http\FormRequest;

class CategoryableUpdateRequest extends FormRequest
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

            'category_id' => 'integer|nullable',
            'categoryable_id' => 'integer|nullable',
            'categoryable_type' => 'string|nullable|max:255',
        ];
    }
}
