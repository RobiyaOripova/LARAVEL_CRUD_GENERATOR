<?php

namespace App\Http\Requests\PageRequest;

use Illuminate\Foundation\Http\FormRequest;


class PageUpdateRequest extends FormRequest
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
			'slug' => 'string|nullable|max:255',
			'description' => 'string|nullable',
			'type' => 'integer|nullable',
			'file_id' => 'integer|nullable',
			'sort' => 'integer|nullable',
			'documents' => 'string|nullable|max:255',
			'anons' => 'string|nullable|max:255',
			'content' => 'string|nullable',
			'lang' => 'integer|nullable',
			'lang_hash' => 'string|nullable|max:255',
			'status' => 'integer|nullable',
        ];
    }
}
