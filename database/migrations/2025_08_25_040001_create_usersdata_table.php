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
        Schema::create('data_karyawan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->foreignId('jabatan_id')->nullable()->constrained('jabatans');

            $table->timestamps();
        });

        Schema::create('data_dosen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->foreignId('jabatan_id')->nullable()->constrained('jabatans');

            $table->timestamps();
        });

        Schema::create('data_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users');
            
            $table->timestamps();
        });

        Schema::create('data_peserta_pmb', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users');
            
            $table->timestamps();
        });

        Schema::create('data_alumni', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_karyawan');
        Schema::dropIfExists('data_dosen');
        Schema::dropIfExists('data_mahasiswa');
        Schema::dropIfExists('data_peserta_pmb');
        Schema::dropIfExists('data_alumni');
    }
};
