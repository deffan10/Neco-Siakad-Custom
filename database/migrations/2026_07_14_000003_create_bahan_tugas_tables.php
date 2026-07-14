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
        Schema::create('bahan_tugas_pertemuan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_pertemuan_id')->constrained('jadwal_pertemuan')->onDelete('cascade');
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->enum('tipe', ['Materi', 'Tugas'])->default('Materi');
            $table->string('file_path')->nullable();
            $table->dateTime('deadline')->nullable();
            
            // Audit
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });

        Schema::create('tugas_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bahan_tugas_pertemuan_id')->constrained('bahan_tugas_pertemuan')->onDelete('cascade');
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->onDelete('cascade');
            $table->string('file_path');
            $table->text('catatan')->nullable();
            $table->integer('nilai')->nullable();

            // Audit
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->unique(['bahan_tugas_pertemuan_id', 'mahasiswa_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tugas_mahasiswa');
        Schema::dropIfExists('bahan_tugas_pertemuan');
    }
};
