<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'username' => 'required|email',
            'password' => 'required',
        ];
    }

    /**
     * [messages description]
     * @Author            Veer          Singh
     * @date              2019-12-24
     * @MethodDescription [Description]
     * @return            [type]        [description]
     */
    public function messages()
    {
        return [
			'username.required' => "Please enter valid email address.",
			'username.email' => "Please enter valid email address.",
			'password.required' => "Please enter password.",
		];
    }
}
