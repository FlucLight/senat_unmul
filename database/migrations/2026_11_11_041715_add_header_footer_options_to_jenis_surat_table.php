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
        Schema::table('jenis_surat', function (Blueprint $table) {
            $table->boolean('ulang_kop')->default(true)->after('margin_kanan');
            $table->boolean('ulang_footer')->default(false)->after('ulang_kop');
            $table->longText('footer_surat')->nullable()->after('ulang_footer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jenis_surat', function (Blueprint $table) {
            $table->dropColumn(['ulang_kop', 'ulang_footer', 'footer_surat']);
        });
    }
};
