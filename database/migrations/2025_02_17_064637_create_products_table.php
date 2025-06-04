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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('name');
            $table->integer('price');
            $table->enum('category', ['Kamera','Elektronik','Outdoor','Kendaraan','Furnitur','Lainnya'])->default('Lainnya');
            $table->string('additional_note')->nullable();
            $table->year('year')->nullable();
            $table->string('address_text');
            $table->decimal('latitude',10,8);
            $table->decimal('longitude',11,8);
            $table->text('description');
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
