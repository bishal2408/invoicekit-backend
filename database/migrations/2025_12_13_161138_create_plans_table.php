<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            // name of the plan
            $table->string('name')->unique();

            // request per minute; null means unlimited
            $table->integer('rate_limit_per_minute')->nullable();

            // request per hour; null means unlimited
            $table->integer('monthly_request_limit')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
