<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->string('slmc_reg_no')->nullable()->after('doctor_type');
            $table->string('license_document')->nullable()->after('slmc_reg_no');
        });
    }

    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->dropColumn(['slmc_reg_no', 'license_document']);
        });
    }
};
