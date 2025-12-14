<?php

namespace App\Models\ApiKey;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'rate_limit_per_minute',
        'monthly_request_limit',
    ];

    /**
     * apiKeys
     *
     * @return HasMany<ApiKey, $this>
     */
    public function apiKeys(): HasMany
    {
        return $this->hasMany(ApiKey::class);
    }
}
