<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('slmc_registrations', function (Blueprint $table) {
            $table->enum('doctor_type', ['VP', 'VOG'])->nullable()->after('registered_name');
        });
    }

    public function down(): void
    {
        Schema::table('slmc_registrations', function (Blueprint $table) {
            $table->dropColumn('doctor_type');
        });
    }
};
