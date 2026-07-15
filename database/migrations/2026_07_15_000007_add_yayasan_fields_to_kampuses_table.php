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
        Schema::table('kampuses', function (Blueprint $table) {
            if (!Schema::hasColumn('kampuses', 'nama_yayasan')) {
                $table->string('nama_yayasan')->nullable()->after('name');
            }
            if (!Schema::hasColumn('kampuses', 'sk_yayasan')) {
                $table->string('sk_yayasan')->nullable()->after('nama_yayasan');
            }
            if (!Schema::hasColumn('kampuses', 'kode_pt')) {
                $table->string('kode_pt', 10)->nullable()->after('sk_yayasan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kampuses', function (Blueprint $table) {
            $table->dropColumn(['nama_yayasan', 'sk_yayasan', 'kode_pt']);
        });
    }
};
