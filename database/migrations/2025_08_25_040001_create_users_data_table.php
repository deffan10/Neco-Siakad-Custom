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
        Schema::create('alamats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->enum('tipe', ['ktp', 'domisili']);
            $table->text('alamat_lengkap');
            $table->string('kelurahan')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kota_kabupaten')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('kode_pos')->nullable();
            $table->string('rt')->nullable();
            $table->string('rw')->nullable();
            
            // Audit
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

        });

        Schema::create('keluargas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->enum('hubungan', ['Ayah', 'Ibu', 'Suami', 'Istri', 'Anak', 'Kakak', 'Adik', 'Wali'])->default('Ayah');
            $table->string('nama');
            $table->string('pekerjaan')->nullable();
            $table->string('telepon')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->bigInteger('penghasilan')->nullable();
            $table->text('alamat')->nullable();

            // Audit
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

        });

        Schema::create('pendidikans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->enum('jenjang', ['Paket C', 'SMA', 'SMK', 'D3', 'S1', 'S2', 'S3'])->default('SMA');
            $table->string('nama_institusi');
            $table->string('jurusan')->nullable();
            $table->integer('tahun_masuk')->nullable();
            $table->integer('tahun_lulus')->nullable();
            $table->string('ipk')->nullable();
            $table->text('alamat')->nullable();

            // Audit
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

        });

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
        Schema::dropIfExists('alamats');
        Schema::dropIfExists('keluargas');
        Schema::dropIfExists('pendidikans');
        Schema::dropIfExists('data_karyawan');
        Schema::dropIfExists('data_dosen');
        Schema::dropIfExists('data_mahasiswa');
        Schema::dropIfExists('data_peserta_pmb');
        Schema::dropIfExists('data_alumni');
    }
};
