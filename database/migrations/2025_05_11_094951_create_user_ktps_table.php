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
            $table->bigInteger('nik')->unique();
            $table->string('nama');
            $table->string('ttl');
            $table->string('alamat');
            $table->string('rt_rw')->nullable();
            $table->string('kel_desa');
            $table->string('kecamatan');
            $table->string('agama');
            $table->string('pekerjaan');
            $table->string('warga_negara');
            $table->string('ktp_url');
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
