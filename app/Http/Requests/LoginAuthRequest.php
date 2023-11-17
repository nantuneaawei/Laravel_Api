<?php

namespace App\Http\Requests;

use App\Rules\ValidAccount;
use Illuminate\Foundation\Http\FormRequest;

class LoginAuthRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'account' => [new ValidAccount, 'required'],
            'password' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'account.required' => 'The account field is required.',
            'password.required' => 'The password field is required.',
        ];
    }

}
