<?php

namespace App\Http\Requests;

use App\Helpers\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class SubscriptionOptinRequest extends FormRequest
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
            'canal' => [
                'nullable',
                'string',
                'max:50',
                'in:api,web,sms,ussd,ivr,mobile_app'
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
            'msisdn.string' => 'The MSISDN number must be a string.',
            'msisdn.regex' => 'The MSISDN number format is not valid.',
            'msisdn.min' => 'The MSISDN number must contain at least 8 characters.',
            'msisdn.max' => 'The MSISDN number cannot exceed 20 characters.',
            'service_offer_id.required' => 'The service offer ID is required.',
            'service_offer_id.integer' => 'The service offer ID must be an integer.',
            'service_offer_id.exists' => 'The specified service offer does not exist.',
            'canal.string' => 'The channel must be a string.',
            'canal.max' => 'The channel cannot exceed 50 characters.',
            'canal.in' => 'The channel must be one of the following: api, web, sms, ussd, ivr, mobile_app.',
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
            'service_offer_id' => 'service offer ID',
            'canal' => 'channel',
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
                'Validation error',
                422
            )
        );
    }
}