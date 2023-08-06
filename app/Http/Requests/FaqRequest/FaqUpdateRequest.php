<?php

namespace App\Http\Requests\FaqRequest;

use Illuminate\Foundation\Http\FormRequest;

class FaqUpdateRequest extends FormRequest
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

            'question' => 'string|nullable',
            'answer' => 'string|nullable',
            'sort' => 'integer|nullable',
            'file_id' => 'integer|nullable',
            'lang' => 'integer|nullable',
            'lang_hash' => 'string|nullable|max:255',
            'status' => 'integer|nullable',
            'type' => 'integer|nullable',
        ];
    }
}
