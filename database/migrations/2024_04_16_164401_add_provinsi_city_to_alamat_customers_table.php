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
        Schema::table('alamat_customers', function (Blueprint $table) {
            $table->string('province_id')->after('kelurahan');
            $table->string('city_id')->after('provinsi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alamat_customers', function (Blueprint $table) {
            $table->dropColumn('province_id');
            $table->dropColumn('city_id');
        });
    }
};
