<?php

namespace App\Http\Requests\MenuItemRequest;

use Illuminate\Foundation\Http\FormRequest;


class MenuItemCreateRequest extends FormRequest
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
            
			'menu_id' => 'integer|required',
			'title' => 'string|required|max:255',
			'url' => 'string|required|max:255',
			'file_id' => 'integer|nullable',
			'sort' => 'integer|nullable',
			'menu_item_parent_id' => 'integer|nullable',
			'status' => 'integer|required',
        ];
    }
}
