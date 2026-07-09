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
        Schema::create('waktu_krs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tahun_akademik_id')->constrained('tahun_akademik')->onDelete('cascade');
            $table->dateTime('tanggal_mulai');
            $table->dateTime('tanggal_selesai');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('syarat_sks', function (Blueprint $table) {
            $table->id();
            $table->decimal('ip_min', 3, 2);
            $table->decimal('ip_max', 3, 2);
            $table->integer('max_sks');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('krs_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('tahun_akademik_id')->constrained('tahun_akademik')->onDelete('cascade');
            $table->enum('status', ['Draft', 'Diajukan', 'Disetujui'])->default('Draft');
            $table->foreignId('dosen_pa_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('catatan_dosen')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('krs_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('krs_id')->constrained('krs_mahasiswa')->onDelete('cascade');
            $table->foreignId('kelas_perkuliahan_id')->constrained('kelas_perkuliahan')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });

        // Add Advisor / Dosen Wali field to data_mahasiswa
        if (Schema::hasTable('data_mahasiswa')) {
            Schema::table('data_mahasiswa', function (Blueprint $table) {
                if (!Schema::hasColumn('data_mahasiswa', 'dosen_pa_id')) {
                    $table->foreignId('dosen_pa_id')->nullable()->constrained('users')->onDelete('set null');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('data_mahasiswa')) {
            Schema::table('data_mahasiswa', function (Blueprint $table) {
                if (Schema::hasColumn('data_mahasiswa', 'dosen_pa_id')) {
                    $table->dropForeign(['dosen_pa_id']);
                    $table->dropColumn('dosen_pa_id');
                }
            });
        }

        Schema::dropIfExists('krs_detail');
        Schema::dropIfExists('krs_mahasiswa');
        Schema::dropIfExists('syarat_sks');
        Schema::dropIfExists('waktu_krs');
    }
};
