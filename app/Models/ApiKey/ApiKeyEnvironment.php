<?php

namespace App\Models\ApiKey;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ApiKeyEnvironment extends Model
{
    /**
     * table
     *
     * @var string
     */
    protected $table = 'api_key_enviroments';

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
