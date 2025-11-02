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

            // Employee Identifiers
            $table->string('nip')->nullable()->unique();  // Nomor Induk Pegawai
            $table->string('nik')->nullable()->unique();  // Nomor Identitas Karyawan

            // Status & Detail
            $table->enum('status_kerja', ['Tetap', 'Kontrak', 'Honorer', 'Outsourcing'])->default('Kontrak');
            $table->date('tanggal_bergabung')->nullable();
            $table->date('tanggal_berakhir_kontrak')->nullable();

            // Additional Info
            $table->string('no_rekening')->nullable();
            $table->string('nama_bank')->nullable();
            $table->string('atas_nama_rekening')->nullable();
            $table->string('npwp')->nullable()->unique();

            // Audit
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });

        Schema::create('data_dosen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->foreignId('jabatan_id')->nullable()->constrained('jabatans');

            // Dosen Identifiers
            $table->string('nidn')->nullable()->unique();   // Nomor Identitas Dosen Nasional
            $table->string('nip')->nullable()->unique();    // Nomor Induk Pegawai (if applicable)

            // Status & Detail
            $table->enum('status_dosen', ['Tetap', 'Kontrak', 'Tidak Tetap', 'Emeritus'])->default('Tetap');
            $table->enum('jenis_dosen', ['Dosen Penuh', 'Dosen Luar Biasa', 'Doswal', 'Guest Lecturer'])->default('Dosen Penuh');
            $table->date('tanggal_bergabung')->nullable();
            $table->date('tanggal_berakhir_kontrak')->nullable();

            // Academic Info
            $table->string('bidang_keahlian')->nullable();
            $table->string('gelar_akademik')->nullable();
            $table->text('riwayat_pendidikan')->nullable();

            // Banking Info
            $table->string('no_rekening')->nullable();
            $table->string('nama_bank')->nullable();
            $table->string('atas_nama_rekening')->nullable();
            $table->string('npwp')->nullable()->unique();

            // Certifications & Honors
            $table->text('sertifikasi')->nullable();

            // Audit
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });

        Schema::create('data_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users');

            // Student Identifiers
            $table->string('nim')->nullable()->unique();    // Nomor Induk Mahasiswa

            // Academic Info
            $table->foreignId('program_studi_id')->nullable()->constrained('program_studi');
            $table->foreignId('status_mahasiswa_id')->nullable()->constrained('status_mahasiswas');
            $table->integer('angkatan')->nullable();        // E.g., 2021, 2022, 2023

            // Dates
            $table->date('tanggal_masuk')->nullable();
            $table->date('tanggal_lulus')->nullable();

            // Academic Standing
            $table->decimal('ipk', 3, 2)->nullable();       // Indeks Prestasi Kumulatif
            $table->integer('sks_lulus')->nullable();       // Semester Kredit Units Completed
            $table->integer('sks_total')->nullable();       // Total SKS Required

            // Sponsorship / Financial
            $table->enum('jenis_pembiayaan', ['Mandiri', 'Beasiswa', 'Beasiswa Penuh', 'Subsidi'])->default('Mandiri');
            $table->string('nama_pemberi_beasiswa')->nullable();

            // Additional Info
            $table->text('asal_sekolah')->nullable();
            $table->enum('jenis_sekolah', ['SMA', 'SMK', 'Pondok Pesantren', 'Lainnya'])->default('SMA');

            // Audit
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });

        Schema::create('data_peserta_pmb', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users');

            // PMB Identifiers
            $table->string('nomor_pendaftaran')->nullable()->unique();

            // Registration Info
            $table->string('program_pilihan_1')->nullable();
            $table->string('program_pilihan_2')->nullable();
            $table->enum('jalur_masuk', ['SNMPTN', 'SBMPTN', 'Mandiri', 'Khusus', 'Transfer'])->default('Mandiri');

            // Academic Info
            $table->integer('tahun_masuk')->nullable();
            $table->enum('status_pendaftaran', ['Menunggu', 'Lolos', 'Tidak Lolos', 'Daftar Ulang', 'Batal'])->default('Menunggu');

            // Dates
            $table->date('tanggal_daftar')->nullable();
            $table->date('tanggal_pengumuman')->nullable();
            $table->date('tanggal_daftar_ulang')->nullable();

            // Test Scores
            $table->decimal('nilai_tes_tulis', 5, 2)->nullable();
            $table->decimal('nilai_wawancara', 5, 2)->nullable();
            $table->decimal('nilai_akhir', 5, 2)->nullable();

            // Additional Info
            $table->text('catatan')->nullable();

            // Audit
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });

        Schema::create('data_alumni', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users');

            // Alumni Identifiers
            $table->string('nomor_alumni')->nullable()->unique();

            // Academic Info
            $table->foreignId('program_studi_id')->nullable()->constrained('program_studi');
            $table->integer('angkatan')->nullable();
            $table->integer('tahun_lulus')->nullable();

            // Graduation Details
            $table->date('tanggal_lulus')->nullable();
            $table->decimal('ipk_akhir', 3, 2)->nullable();
            $table->string('predikat_lulus')->nullable();   // Cumlaude, Sangat Memuaskan, etc.

            // Career Info
            $table->enum('status_pekerjaan', ['Bekerja', 'Belum Bekerja', 'Melanjutkan Studi', 'Wiraswasta'])->nullable();
            $table->string('bidang_pekerjaan')->nullable();
            $table->string('instansi_pekerjaan')->nullable();
            $table->string('jabatan_pekerjaan')->nullable();
            $table->string('lokasi_pekerjaan')->nullable();
            $table->integer('tahun_mulai_bekerja')->nullable();

            // Further Education
            $table->text('melanjutkan_ke')->nullable();     // University name if continuing studies

            // Audit & Contact Info
            $table->text('catatan')->nullable();
            $table->date('tahun_pembaruan_data')->nullable();

            // Audit
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
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
