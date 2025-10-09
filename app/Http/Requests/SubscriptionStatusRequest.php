<?php

namespace App\Http\Requests;

use App\Helpers\ApiResponse;
use App\Rules\ValidPartner;
use App\Rules\ValidServiceOffer;
use App\Rules\ValidSubscriberWithOffer;
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
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        // On récupère le paramètre 'partner_id' de la route
        // et on l'ajoute aux données de la requête.
        $this->merge([
            'partner_id' => $this->route('partner_id'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'partner_id' => ['required', 'string', new ValidPartner()],
            'msisdn' => [
                'required',
                'string',
                'regex:/^[0-9+\-\s()]+$/',
                'min:8',
                'max:20',
                'exists:subscribers,msisdn',
            ],
            'service_offer_id' => [
                'required',
                'integer',
                new ValidServiceOffer($this->partner_id ?? ''),
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
            'partner_id.required' => 'The partner ID is required.',
            'partner_id.string' => 'The partner ID must be a string.',
            'msisdn.required' => 'The MSISDN number is required.',
            'msisdn.regex' => 'The MSISDN number format is not valid.',
            'msisdn.min' => 'The MSISDN number must contain at least 8 characters.',
            'msisdn.max' => 'The MSISDN number cannot exceed 20 characters.',
            'msisdn.exists' => 'The MSISDN number does not exist in the system.',
            'service_offer_id.required' => 'The Service Offer ID is required.',
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
            'partner_id' => 'Partner ID',
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