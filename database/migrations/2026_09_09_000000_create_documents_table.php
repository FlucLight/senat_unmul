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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis', ['undangan', 'berita_acara', 'surat_pengantar', 'daftar_hadir']);
            $table->string('nomor_surat')->nullable();
            $table->string('judul');
            $table->enum('status', ['draft', 'final'])->default('draft');
            $table->foreignId('created_by')->constrained('users');
            $table->json('content')->nullable();
            $table->string('pdf_path')->nullable();
            $table->string('qr_verification_url')->nullable();
            $table->timestamp('finalized_at')->nullable();
            $table->timestamps();

            $table->index(['jenis', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};