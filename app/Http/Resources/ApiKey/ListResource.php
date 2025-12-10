<?php

namespace App\Http\Resources\ApiKey;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ListResource extends JsonResource
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
            'name' => $this->name,
            'key_prefix' => $this->key_prefix,
            'environment_name' => $this->environment_name,
            'user_id' => $this->user_id,
            'user_name' => $this->user_name,
            'user_email' => $this->user_email,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'expires_at' => $this->expires_at,
            'last_used_at' => $this->last_used_at,
        ];
    }
}
