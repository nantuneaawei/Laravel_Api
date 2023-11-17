<?php

namespace App\Http\Requests;

use App\Rules\ValidAccount;
use Illuminate\Foundation\Http\FormRequest;

class CreatePlayerRequest extends FormRequest
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
            'account' => ['required', new ValidAccount, 'unique:Player,account'],
            'password' => 'required',
            'displayName' => 'required',
            'email' => 'required|email',
            'status' => 'required|in:active,blocked,inactive',
            'balance' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'account.required' => 'The account field is required.',
            'account.unique' => 'The account has already been taken.',
            'password.required' => 'The password field is required.',
            'displayName.required' => 'The display name field is required.',
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'status.required' => 'The status field is required.',
            'status.in' => 'The status must be one of: active, blocked, or inactive.',
            'balance.required' => 'The balance field is required.',
            'balance.numeric' => 'The balance must be a number.',
        ];
    }

}
