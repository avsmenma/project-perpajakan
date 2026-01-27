<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('data_karyawan', function (Blueprint $table) {
            $table->string('npwp', 20)->primary();
            $table->string('nama', 100);
            $table->string('nik_sap', 50)->nullable();
            $table->string('nama_kebun', 100)->nullable();
            $table->string('bupot_a1', 100)->nullable();
            $table->string('link_pdf', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_karyawan');
    }
};
