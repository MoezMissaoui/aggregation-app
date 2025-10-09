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
            'service_offer_id' => [
                'required',
                'integer',
                'exists:service_offers,id'
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
            'msisdn.required' => 'The MSISDN number is required.',
            'msisdn.regex' => 'The MSISDN number format is not valid.',
            'msisdn.min' => 'The MSISDN number must contain at least 8 characters.',
            'msisdn.max' => 'The MSISDN number cannot exceed 20 characters.',
            'service_offer_id.required' => 'The Service Offer ID is required.',
            'service_offer_id.exists' => 'The Service Offer ID does not exist.',
            'service_offer_id.integer' => 'The Service Offer ID must be an integer.',
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
            'msisdn' => 'MSISDN number',
            'service_offer_id' => 'Service Offer ID',
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
                'Validation Error',
                422
            )
        );
    }
}