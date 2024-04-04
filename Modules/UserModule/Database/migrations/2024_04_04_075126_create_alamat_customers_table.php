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
        Schema::create('alamat_customers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->string('nama_penerima');
            $table->string('nomor_telepon');
            $table->longText('alamat');
            $table->string('kota');
            $table->string('kecamatan');
            $table->string('kelurahan');
            $table->string('provinsi');
            $table->string('kode_pos');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->softDeletes();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alamat_customers');
    }
};
