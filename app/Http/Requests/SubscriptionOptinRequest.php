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
            'msisdn.required' => 'Le numéro MSISDN est requis.',
            'msisdn.regex' => 'Le format du numéro MSISDN n\'est pas valide.',
            'msisdn.min' => 'Le numéro MSISDN doit contenir au moins 8 caractères.',
            'msisdn.max' => 'Le numéro MSISDN ne peut pas dépasser 20 caractères.',
            'service_offer_id.required' => 'L\'ID de l\'offre de service est requis.',
            'service_offer_id.integer' => 'L\'ID de l\'offre de service doit être un entier.',
            'service_offer_id.exists' => 'L\'offre de service spécifiée n\'existe pas.',
            'canal.string' => 'Le canal doit être une chaîne de caractères.',
            'canal.max' => 'Le canal ne peut pas dépasser 50 caractères.',
            'canal.in' => 'Le canal doit être l\'un des suivants: api, web, sms, ussd, ivr, mobile_app.',
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
            'service_offer_id' => 'ID de l\'offre de service',
            'canal' => 'canal',
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