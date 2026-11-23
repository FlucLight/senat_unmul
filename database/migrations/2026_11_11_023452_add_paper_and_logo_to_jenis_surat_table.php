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
            $table->string('logo')->nullable()->after('template_surat');
            $table->string('ukuran_kertas', 20)->default('a4')->after('logo');
            $table->decimal('margin_atas', 4, 1)->default(3.0)->after('ukuran_kertas');
            $table->decimal('margin_bawah', 4, 1)->default(3.0)->after('margin_atas');
            $table->decimal('margin_kiri', 4, 1)->default(4.0)->after('margin_bawah');
            $table->decimal('margin_kanan', 4, 1)->default(3.0)->after('margin_kiri');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jenis_surat', function (Blueprint $table) {
            $table->dropColumn([
                'logo',
                'ukuran_kertas',
                'margin_atas',
                'margin_bawah',
                'margin_kiri',
                'margin_kanan',
            ]);
        });
    }
};
