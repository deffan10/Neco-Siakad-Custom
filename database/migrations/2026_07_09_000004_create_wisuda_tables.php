<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kegiatan_wisuda', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tahun_akademik_id')->constrained('tahun_akademik')->onDelete('cascade');
            $table->string('nama');
            $table->date('tanggal_mulai_daftar');
            $table->date('tanggal_selesai_daftar');
            $table->date('tanggal_pelaksanaan');
            $table->integer('kuota');
            $table->decimal('biaya', 15, 2);
            $table->boolean('status')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('deleted_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('pendaftaran_wisuda', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_wisuda_id')->constrained('kegiatan_wisuda')->onDelete('cascade');
            $table->foreignId('mahasiswa_id')->constrained('data_mahasiswa')->onDelete('cascade');
            $table->text('judul_skripsi');
            $table->string('ukuran_toga');
            $table->integer('toefl_score')->nullable();
            $table->enum('status', ['Draft', 'Diajukan', 'Disetujui', 'Ditolak'])->default('Draft');
            $table->text('catatan')->nullable();
            $table->string('berkas_photo')->nullable();
            $table->string('berkas_bebas_pustaka')->nullable();
            $table->string('berkas_skripsi')->nullable();
            $table->string('berkas_toefl')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftaran_wisuda');
        Schema::dropIfExists('kegiatan_wisuda');
    }
};
