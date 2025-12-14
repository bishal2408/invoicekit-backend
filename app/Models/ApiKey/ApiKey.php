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
        'plan_id',
        'override_rate_limit_per_minute',
        'override_monthly_request_limit',
        'override_expires_at',
    ];

    /**
     * casts
     *
     * @var array<string, string>
     */
    protected $casts = [
        'expires_at' => 'datetime',
        'last_used_at' => 'datetime',
        'override_expires_at' => 'datetime',
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
     * plan
     *
     * @return BelongsTo<Plan, $this>
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class, 'plan_id');
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

    /**
     * hasActiveOverride
     *
     * @return bool
     */
    public function hasActiveOverride()
    {
        return $this->override_expires_at
            && now()->lessThan($this->override_expires_at);
    }

    /**
     * getEffectiveRateLimitPerMinute
     *
     * @return int|null
     */
    public function getEffectiveRateLimitPerMinute()
    {
        if ($this->hasEffectiveRateLimitPerMinute()) {
            return $this->override_rate_limit_per_minute;
        }

        return $this->plan?->rate_limit_per_minute;
    }

    /**
     * getEffectiveMonthlyRequestLimit
     *
     * @return int|null
     */
    public function getEffectiveMonthlyRequestLimit()
    {
        if ($this->hasEffectiveMonthlyRequestLimit()) {
            return $this->override_monthly_request_limit;
        }

        return $this->plan?->monthly_request_limit;
    }

    /**
     * hasEffectiveMonthlyRequestLimit
     *
     * @return bool
     */
    public function hasEffectiveMonthlyRequestLimit()
    {
        return $this->hasActiveOverride()
            && $this->override_monthly_request_limit;
    }

    /**
     * hasEffectiveRateLimitPerMinute
     *
     * @return bool
     */
    public function hasEffectiveRateLimitPerMinute()
    {
        return $this->hasActiveOverride()
            && $this->override_rate_limit_per_minute;
    }
}
