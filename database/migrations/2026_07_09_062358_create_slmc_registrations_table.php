<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('slmc_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('slmc_reg_no')->unique();
            $table->string('registered_name')->nullable();
            $table->boolean('is_used')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('slmc_registrations');
    }
};
