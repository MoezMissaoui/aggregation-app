<?php

namespace App\Http\Requests;

use App\Helpers\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class SubscriptionStatusRequest extends FormRequest
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
            'msisdn' => [
                'required',
                'string',
                'regex:/^[0-9+\-\s()]+$/',
                'min:8',
                'max:20'
            ],
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
            'msisdn.required' => 'Le numéro MSISDN est requis.',
            'msisdn.regex' => 'Le format du numéro MSISDN n\'est pas valide.',
            'msisdn.min' => 'Le numéro MSISDN doit contenir au moins 8 caractères.',
            'msisdn.max' => 'Le numéro MSISDN ne peut pas dépasser 20 caractères.',
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
            'msisdn' => 'numéro MSISDN',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            ApiResponse::error(
                ['errors' => $validator->errors()],
                'Erreur de validation',
                422
            )
        );
    }
}