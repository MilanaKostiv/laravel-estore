<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Checkout form request validation.
 */
class CheckoutRequest extends FormRequest
{
    /**
     * Determines if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() : bool
    {
        return true;
    }

    /**
     * Gets the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $emailValidation = auth()->user() ? 'required|email': 'required|email|unique:users';

        return [
            'email' => $emailValidation,
            'name' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'postal_code' => 'required',
            'phone' => 'required'
        ];
    }

    /**
     * Gets custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'email.unique' => 'You already have an account with this email address. Please <a href="/login">login</a> to continue.'
        ];
    }
}
