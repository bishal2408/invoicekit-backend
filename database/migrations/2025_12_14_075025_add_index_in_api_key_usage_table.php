<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('api_key_usages', function (Blueprint $table) {
            $table->index('api_key_id');
            $table->index(['api_key_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::table('api_key_usages', function (Blueprint $table) {
            $table->dropIndex(['api_key_id', 'created_at']);
        });
    }
};
