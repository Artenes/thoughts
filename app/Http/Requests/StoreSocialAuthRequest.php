<?php

namespace Thoughts\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request to store a new user through social login.
 * @package Thoughts\Http\Requests
 */
class StoreSocialAuthRequest extends FormRequest
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
            'service' => 'required|in:facebook',
            'token' => 'required',
        ];
    }
}
