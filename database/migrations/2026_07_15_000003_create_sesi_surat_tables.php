<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sesi_kuliah', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->string('nama');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('tipe')->default('Kuliah'); // Kuliah, Ujian
            $table->string('kelompok')->nullable(); // Pagi, Siang, Malam
            $table->integer('durasi_menit')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('surat_tugas_mengajar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dosen_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('tahun_akademik_id')->nullable()->constrained('tahun_akademik')->nullOnDelete();
            $table->string('nomor_surat')->nullable();
            $table->text('perihal')->nullable();
            $table->date('tanggal_surat')->nullable();
            $table->string('status')->default('Aktif');
            $table->string('template')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_tugas_mengajar');
        Schema::dropIfExists('sesi_kuliah');
    }
};
