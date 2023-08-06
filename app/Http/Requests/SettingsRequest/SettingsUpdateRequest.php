<?php

namespace App\Http\Requests\SettingsRequest;

use Illuminate\Foundation\Http\FormRequest;


class SettingsUpdateRequest extends FormRequest
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
            
			'name' => 'string|nullable|max:255',
			'value' => 'string|nullable|max:255',
			'file_id' => 'integer|nullable',
			'slug' => 'string|nullable|max:255',
			'link' => 'string|nullable|max:255',
			'alias' => 'string|nullable|max:255',
			'lang_hash' => 'string|nullable|max:255',
			'sort' => 'integer|nullable',
			'lang' => 'integer|nullable',
			'status' => 'integer|nullable',
        ];
    }
}
