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
        Schema::create('users_statuses', function (Blueprint $table) {
            $table->id();
            $table->enum('role', ['admin','user']);
            $table->boolean('is_banned')->default(false);
            $table->boolean('is_premium')->default(false);
            $table->boolean('is_verified')->default(false);
            $table->dateTime('last_seen')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_statuses');
    }
};
