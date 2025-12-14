<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('api_keys', function (Blueprint $table) {
            // plan id
            $table->foreignId('plan_id')
                ->nullable()
                ->after('user_id')
                ->constrained()
                ->nullOnDelete();

            // temporary overrides
            // rate limit per minute
            $table->integer('override_rate_limit_per_minute')->nullable()
                ->after('plan_id');

            // monthly request limit
            $table->integer('override_monthly_request_limit')->nullable()
                ->after('override_rate_limit_per_minute');

            // override expires at
            $table->timestamp('override_expires_at')
                ->nullable()
                ->after('override_monthly_request_limit');
        });
    }

    public function down(): void
    {
        Schema::table('api_keys', function (Blueprint $table) {
            $table->dropForeign(['plan_id']);

            $table->dropColumn([
                'plan_id',
                'override_rate_limit_per_minute',
                'override_monthly_request_limit',
                'override_expires_at',
            ]);
        });
    }
};
