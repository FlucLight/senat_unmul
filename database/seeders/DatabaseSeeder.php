<?php

namespace Database\Seeders;

use App\Models\JenisSurat;
use App\Models\Surat;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $jenisSurat = JenisSurat::factory()->create([
            'nama_surat' => 'Surat Keputusan Senat',
        ]);

        Surat::factory()->create([
            'jenis_surat_id' => $jenisSurat->id,
            'nomor_surat' => '001/SN/FT/UNMUL/2026',
            'tanggal_surat' => now(),
            'konten_surat' => config('senatitor.default_template'),
        ]);
    }
}
