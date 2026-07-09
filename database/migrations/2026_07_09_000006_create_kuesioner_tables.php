<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kuesioner', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->enum('target_responden', ['Alumni', 'Mahasiswa', 'Dosen', 'Umum'])->default('Mahasiswa');
            $table->boolean('is_published')->default(false);
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('kuesioner_pertanyaan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kuesioner_id')->constrained('kuesioner')->onDelete('cascade');
            $table->text('pertanyaan');
            $table->enum('tipe', ['text', 'radio', 'checkbox'])->default('text');
            $table->json('pilihan_jawaban')->nullable();
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });

        Schema::create('kuesioner_respons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kuesioner_id')->constrained('kuesioner')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamps();
        });

        Schema::create('kuesioner_respons_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('respons_id')->constrained('kuesioner_respons')->onDelete('cascade');
            $table->foreignId('pertanyaan_id')->constrained('kuesioner_pertanyaan')->onDelete('cascade');
            $table->text('jawaban');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kuesioner_respons_detail');
        Schema::dropIfExists('kuesioner_respons');
        Schema::dropIfExists('kuesioner_pertanyaan');
        Schema::dropIfExists('kuesioner');
    }
};
