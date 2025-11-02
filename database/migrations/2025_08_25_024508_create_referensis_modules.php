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
        Schema::create('agamas', function (Blueprint $table) {
            $table->id();
            $table->string('name');

            // Audit
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

        });
        
        Schema::create('golongan_darahs', function (Blueprint $table) {
            $table->id();
            $table->string('name');

            // Audit
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

        });

        Schema::create('jabatans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('divisi');

            // Audit
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

        });
        
        Schema::create('jenis_kelamins', function (Blueprint $table) {
            $table->id();
            $table->string('name');

            // Audit
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });
        Schema::create('kewarganegaraans', function (Blueprint $table) {
            $table->id();
            $table->string('name');

            // Audit
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

        });

        Schema::create('semesters', function (Blueprint $table) {
            $table->id();
            $table->string('name');

            // Audit
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

        });

        Schema::create('status_mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->string('name');

            // Audit
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agamas');
        Schema::dropIfExists('golongan_darahs');
        Schema::dropIfExists('jabatans');
        Schema::dropIfExists('jenis_kelamins');
        Schema::dropIfExists('kewarganegaraans');
        Schema::dropIfExists('semesters');
        Schema::dropIfExists('status_mahasiswas');
    }
};
