<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesan_internal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengirim_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('penerima_id')->constrained('users')->cascadeOnDelete();
            $table->string('subjek');
            $table->text('isi');
            $table->boolean('dibaca')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesan_internal');
    }
};
