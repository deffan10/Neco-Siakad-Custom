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
        if (Schema::hasTable('pendaftaran_wisuda')) {
            Schema::table('pendaftaran_wisuda', function (Blueprint $table) {
                if (!Schema::hasColumn('pendaftaran_wisuda', 'kehadiran_upacara')) {
                    $table->boolean('kehadiran_upacara')->default(false);
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('pendaftaran_wisuda')) {
            Schema::table('pendaftaran_wisuda', function (Blueprint $table) {
                if (Schema::hasColumn('pendaftaran_wisuda', 'kehadiran_upacara')) {
                    $table->dropColumn('kehadiran_upacara');
                }
            });
        }
    }
};
