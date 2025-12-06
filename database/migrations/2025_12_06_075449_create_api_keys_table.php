<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('api_keys', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('environment_id');
            $table->string('name')->nullable();
            $table->string('key_prefix', 32)->index();
            $table->string('key_hash')->unique();
            $table->boolean('is_active')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            // define foreign keys
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('environment_id')->references('id')->on('api_key_enviroments')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_keys');
    }
};
