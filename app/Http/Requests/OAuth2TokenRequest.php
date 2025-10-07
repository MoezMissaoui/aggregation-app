<?php

namespace App\Http\Requests;

use App\Helpers\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Response;

class OAuth2TokenRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'client_id' => 'required|string|exists:partners,partner_id',
            'client_secret' => 'required|string',
            'grant_type' => 'required|string|in:client_credentials'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'client_id.required' => 'The client ID is required.',
            'client_id.string' => 'The client ID must be a string.',
            'client_id.exists' => 'The provided client ID does not exist.',
            'client_secret.required' => 'The client secret is required.',
            'client_secret.string' => 'The client secret must be a string.',
            'grant_type.required' => 'The grant type is required.',
            'grant_type.string' => 'The grant type must be a string.',
            'grant_type.in' => 'The grant type must be client_credentials.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'client_id' => 'client ID',
            'client_secret' => 'client secret',
            'grant_type' => 'grant type',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            ApiResponse::error(
                ['errors' => $validator->errors()],
                'Validation failed',
                Response::HTTP_BAD_REQUEST
            )
        );
    }
}