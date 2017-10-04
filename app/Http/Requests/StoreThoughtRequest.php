<?php

namespace Thoughts\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request to store a thought.
 *
 * @package Thoughts\Http\Requests
 */
class StoreThoughtRequest extends FormRequest
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
            'body' => 'required|max:255'
        ];
    }

}
