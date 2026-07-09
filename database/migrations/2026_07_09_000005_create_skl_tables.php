<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('data_mahasiswa')->onDelete('cascade');
            $table->string('nomor_skl')->unique();
            $table->date('tanggal_lulus');
            $table->decimal('ipk', 4, 2);
            $table->string('yudisium');
            $table->text('judul_skripsi');
            $table->string('pejabat_penandatangan');
            $table->string('jabatan_penandatangan');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('deleted_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skls');
    }
};
