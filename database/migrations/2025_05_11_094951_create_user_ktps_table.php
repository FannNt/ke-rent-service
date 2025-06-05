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
        Schema::create('user_ktps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique();
            $table->bigInteger('nik')->unique()->nullable();
            $table->string('nama')->nullable();
            $table->string('ttl')->nullable();
            $table->string('alamat')->nullable();
            $table->string('rt_rw')->nullable();
            $table->string('kel_desa')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('agama')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->string('warga_negara')->nullable();
            $table->string('ktp_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_ktps');
    }
};
