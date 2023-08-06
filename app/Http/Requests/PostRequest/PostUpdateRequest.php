<?php

namespace App\Http\Requests\PostRequest;

use Illuminate\Foundation\Http\FormRequest;


class PostUpdateRequest extends FormRequest
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
			'description' => 'string|nullable|max:500',
			'content' => 'string|nullable',
			'slug' => 'string|nullable|max:255',
			'popular' => 'integer|nullable',
			'type' => 'integer|nullable',
			'file_id' => 'integer|nullable',
			'document_ids' => 'string|nullable|max:255',
			'category_ids' => 'string|nullable|max:255',
			'video_id' => 'integer|nullable',
			'top' => 'integer|nullable',
			'views' => 'integer|nullable',
			'published_at' => 'datetime|nullable',
			'lang' => 'integer|nullable',
			'lang_hash' => 'string|nullable|max:255',
			'status' => 'integer|nullable',
        ];
    }
}
