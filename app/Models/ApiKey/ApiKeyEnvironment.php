<?php

namespace App\Models\ApiKey;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ApiKeyEnvironment extends Model
{
    /**
     * fillable
     *
     * @var list<string>
     */
    protected $fillbale = [
        'name',
        'description',
    ];

    /**
     * keys
     *
     * @return HasMany<ApiKey, $this>
     */
    public function keys(): HasMany
    {
        return $this->hasMany(ApiKey::class, 'environment_id');
    }
}
