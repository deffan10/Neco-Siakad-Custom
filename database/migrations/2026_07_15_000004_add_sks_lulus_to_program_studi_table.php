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
        Schema::table('program_studi', function (Blueprint $table) {
            if (!Schema::hasColumn('program_studi', 'sks_lulus')) {
                $table->integer('sks_lulus')->default(144)->after('gelar_belakang');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('program_studi', function (Blueprint $table) {
            if (Schema::hasColumn('program_studi', 'sks_lulus')) {
                $table->dropColumn('sks_lulus');
            }
        });
    }
};
