<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori_pengumuman', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->timestamps();
        });

        Schema::create('pengumuman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('kategori_id')->nullable()->constrained('kategori_pengumuman')->nullOnDelete();
            $table->string('judul');
            $table->text('isi');
            $table->string('target')->default('Semua'); // Semua, Mahasiswa, Dosen, Operator
            $table->boolean('is_published')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('dispensasi_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('tahun_akademik_id')->nullable()->constrained('tahun_akademik')->nullOnDelete();
            $table->string('jenis'); // Sakit, Izin, Tugas, dll
            $table->text('alasan');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('status')->default('Pending'); // Pending, Disetujui, Ditolak
            $table->text('catatan_admin')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dispensasi_mahasiswa');
        Schema::dropIfExists('pengumuman');
        Schema::dropIfExists('kategori_pengumuman');
    }
};
