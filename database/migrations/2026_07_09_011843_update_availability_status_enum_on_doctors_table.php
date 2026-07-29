<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE doctors MODIFY availability_status ENUM('Available', 'Not Available') NOT NULL DEFAULT 'Not Available'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE doctors MODIFY availability_status ENUM('Available', 'Busy', 'In OPD', 'On Leave', 'Off Duty') NOT NULL DEFAULT 'Available'");
    }
};

