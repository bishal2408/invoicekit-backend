<?php

namespace App\Models\ApiKey;

use Illuminate\Database\Eloquent\Model;

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
}
