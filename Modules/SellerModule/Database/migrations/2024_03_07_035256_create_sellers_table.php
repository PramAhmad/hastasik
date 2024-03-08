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
        Schema::create('sellers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string("nama_pemilik");
            $table->integer("no_hp");
            $table->string("email");
            $table->string("nama_toko");
            $table->string("foto");
            $table->string("deskripsi");
            $table->string("kota");
            $table->string("kecamatan");
            $table->string("kelurahan");
            $table->text("alamat");
            $table->string("longitude");
        //    laltitute
            $table->integer("latitude");
            $table->integer("status");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sellers');
    }
};
