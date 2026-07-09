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
        Schema::create('nilai_komponen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas_perkuliahan')->onDelete('cascade');
            $table->string('nama_komponen'); // e.g., UTS, UAS, Tugas
            $table->integer('bobot_persen'); // e.g., 30, 40, 30
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('nilai_kuliah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_mahasiswa_id')->constrained('kelas_mahasiswa')->onDelete('cascade');
            $table->decimal('nilai_tugas', 5, 2)->default(0);
            $table->decimal('nilai_uts', 5, 2)->default(0);
            $table->decimal('nilai_uas', 5, 2)->default(0);
            $table->decimal('nilai_akhir_angka', 5, 2)->default(0);
            $table->string('nilai_huruf', 2)->default('E');
            $table->decimal('bobot_indeks', 3, 2)->default(0.00); // e.g., 4.00, 3.00, 2.00, etc.
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('akumulasi_ip', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // mahasiswa
            $table->foreignId('tahun_akademik_id')->constrained('tahun_akademik')->onDelete('cascade');
            $table->decimal('ips', 3, 2)->default(0.00);
            $table->decimal('ipk', 3, 2)->default(0.00);
            $table->integer('total_sks')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akumulasi_ip');
        Schema::dropIfExists('nilai_kuliah');
        Schema::dropIfExists('nilai_komponen');
    }
};
