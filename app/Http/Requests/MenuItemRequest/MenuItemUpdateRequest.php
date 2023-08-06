<?php

namespace App\Http\Requests\MenuItemRequest;

use Illuminate\Foundation\Http\FormRequest;


class MenuItemUpdateRequest extends FormRequest
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
            
			'menu_id' => 'integer|nullable',
			'title' => 'string|nullable|max:255',
			'url' => 'string|nullable|max:255',
			'file_id' => 'integer|nullable',
			'sort' => 'integer|nullable',
			'menu_item_parent_id' => 'integer|nullable',
			'status' => 'integer|nullable',
        ];
    }
}
