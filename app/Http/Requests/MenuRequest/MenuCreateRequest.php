<?php

namespace App\Http\Requests\MenuRequest;

use Illuminate\Foundation\Http\FormRequest;


class MenuCreateRequest extends FormRequest
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
            
			'title' => 'string|nullable|max:255',
			'alias' => 'string|nullable|max:255',
			'type' => 'integer|nullable',
			'lang' => 'integer|nullable',
			'lang_hash' => 'string|nullable|max:255',
			'status' => 'integer|required',
        ];
    }
}
