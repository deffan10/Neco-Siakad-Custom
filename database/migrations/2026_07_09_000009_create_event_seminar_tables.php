<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel Event Seminar
        Schema::create('events_seminar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tahun_akademik_id')->nullable()->constrained('tahun_akademik')->onDelete('set null');
            $table->string('nama_event');
            $table->enum('tipe_event', ['Proposal', 'Hasil', 'Umum', 'Webinar', 'Workshop'])->default('Umum');
            $table->text('deskripsi')->nullable();
            $table->date('tanggal');
            $table->time('jam_mulai')->nullable();
            $table->time('jam_selesai')->nullable();
            $table->string('lokasi')->nullable();
            $table->string('narasumber')->nullable();
            $table->integer('kuota')->nullable();
            $table->boolean('is_wajib')->default(false);
            $table->boolean('is_open')->default(true); // open for registration
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
        });

        // Tabel Peserta Seminar (pivot)
        Schema::create('event_seminar_peserta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events_seminar')->onDelete('cascade');
            $table->foreignId('mahasiswa_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['Mendaftar', 'Hadir', 'Tidak Hadir'])->default('Mendaftar');
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->unique(['event_id', 'mahasiswa_id']);
        });

        // Tabel Sertifikasi Mahasiswa (SKPI)
        Schema::create('sertifikasi_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('users')->onDelete('cascade');
            $table->string('nama_sertifikat');
            $table->string('lembaga_penerbit');
            $table->string('nomor_sertifikat')->nullable();
            $table->date('tanggal_terbit');
            $table->date('tanggal_kadaluarsa')->nullable();
            $table->enum('kategori', ['Bahasa', 'Teknologi', 'Profesi', 'Soft Skill', 'Lainnya'])->default('Lainnya');
            $table->string('file_sertifikat')->nullable();
            $table->enum('status_verifikasi', ['Menunggu', 'Disetujui', 'Ditolak'])->default('Menunggu');
            $table->text('catatan_verifikasi')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sertifikasi_mahasiswa');
        Schema::dropIfExists('event_seminar_peserta');
        Schema::dropIfExists('events_seminar');
    }
};
