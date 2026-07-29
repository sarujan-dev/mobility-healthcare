<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::create('patients', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->date('dob')->nullable();
        $table->enum('gender', ['Male', 'Female', 'Other'])->nullable();
        $table->text('address')->nullable();
        $table->decimal('lat', 10, 7)->nullable();
        $table->decimal('lng', 10, 7)->nullable();
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('patients');
}
};
