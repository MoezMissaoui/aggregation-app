<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class PartnerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'partner_id' => $this->partner_id,
            'partner_secret' => $this->partner_secret,
            'name' => $this->name,
            'description' => $this->description,
            'billing_address' => $this->billing_adress, // Note: keeping the typo from migration
            'contacts' => $this->contacts ? json_decode($this->contacts, true) : null,
            'commercial_contacts' => $this->commercial_contacts ? json_decode($this->commercial_contacts, true) : null,
            'technical_contacts' => $this->technical_contacts ? json_decode($this->technical_contacts, true) : null,
            'url' => $this->url,
            'is_active' => $this->is_active,
            'services' => ServiceResource::collection($this->whenLoaded('services')),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}