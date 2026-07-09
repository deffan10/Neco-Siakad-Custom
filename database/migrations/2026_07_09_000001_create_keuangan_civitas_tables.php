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
        Schema::create('komponen_biaya', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->decimal('default_amount', 12, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('tarif_biaya', function (Blueprint $table) {
            $table->id();
            $table->foreignId('komponen_id')->constrained('komponen_biaya')->onDelete('cascade');
            $table->foreignId('program_studi_id')->constrained('program_studi')->onDelete('cascade');
            $table->integer('angkatan'); // e.g., 2022, 2023
            $table->decimal('nominal', 12, 2);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('tagihan_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // mahasiswa
            $table->foreignId('tahun_akademik_id')->constrained('tahun_akademik')->onDelete('cascade');
            $table->decimal('total_tagihan', 12, 2);
            $table->enum('status', ['Belum Lunas', 'Lunas', 'Kurang Bayar'])->default('Belum Lunas');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('pembayaran_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tagihan_id')->constrained('tagihan_mahasiswa')->onDelete('cascade');
            $table->decimal('nominal', 12, 2);
            $table->string('channel_pembayaran')->default('Virtual Account');
            $table->string('referensi_transaksi')->unique();
            $table->enum('status', ['Pending', 'Success', 'Failed'])->default('Pending');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran_mahasiswa');
        Schema::dropIfExists('tagihan_mahasiswa');
        Schema::dropIfExists('tarif_biaya');
        Schema::dropIfExists('komponen_biaya');
    }
};
