<?php

namespace App\Http\Requests\FaqRequest;

use Illuminate\Foundation\Http\FormRequest;

class FaqCreateRequest extends FormRequest
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

            'question' => 'string|required',
            'answer' => 'string|required',
            'sort' => 'integer|required',
            'file_id' => 'integer|nullable',
            'lang' => 'integer|nullable',
            'lang_hash' => 'string|nullable|max:255',
            'status' => 'integer|required',
            'type' => 'integer|required',
        ];
    }
}
