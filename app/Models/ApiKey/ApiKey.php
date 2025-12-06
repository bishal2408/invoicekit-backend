<?php

namespace App\Models\ApiKey;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApiKey extends Model
{
    use SoftDeletes;

    /**
     * fillable
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'environment_id',
        'name',
        'key_prefix',
        'key_hash',
        'is_active',
        'expires_at',
        'last_used_at',
        'deleted_at',
    ];

    /**
     * casts
     *
     * @var array<string, string>
     */
    protected $casts = [
        'expires_at' => 'datetime',
        'last_used_at' => 'datetime',
    ];

    /**
     * environment
     *
     * @return BelongsTo<ApiKeyEnvironment, $this>
     */
    public function environment(): BelongsTo
    {
        return $this->belongsTo(ApiKeyEnvironment::class, 'environment_id');
    }

    /**
     * user
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * isExpired
     *
     * @return bool
     */
    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * touchLastUsed
     *
     * @return void
     */
    public function touchLastUsed()
    {
        // last used date
        $this->last_used_at = now();

        // save quietly
        $this->saveQuietly();
    }
}
