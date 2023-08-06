<?php

namespace App\Http\Requests\BannerRequest;

use Illuminate\Foundation\Http\FormRequest;

class BannerUpdateRequest extends FormRequest
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

            'title_uz' => 'string|nullable|max:255',
            'title_ru' => 'string|nullable|max:255',
            'title_en' => 'string|nullable|max:255',
            'description_uz' => 'string|nullable',
            'description_ru' => 'string|nullable',
            'description_en' => 'string|nullable',
            'url' => 'string|nullable|max:255',
            'viewed' => 'integer|nullable',
            'file_id' => 'integer|nullable',
            'sort' => 'integer|nullable',
            'status' => 'integer|nullable',
        ];
    }
}
