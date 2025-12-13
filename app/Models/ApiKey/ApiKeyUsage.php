<?php

namespace App\Models\ApiKey;

use Illuminate\Database\Eloquent\Model;

class ApiKeyUsage extends Model
{
    /**
     * fillable
     *
     * @var list<string>
     */
    protected $fillable = [
        'api_key_id',
        'endpoint',
        'method',
        'status_code',
        'ip_address',
        'user_agent',
        'response_time_ms',
    ];
}
