<?php

namespace Database\Factories;

use App\Models\JenisSurat;
use App\Models\Surat;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Surat>
 */
class SuratFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'jenis_surat_id' => JenisSurat::factory(),
            'nomor_surat' => fake()->numerify('###/SN/FT/UNMUL/2026'),
            'tanggal_surat' => fake()->date(),
            'konten_surat' => '<p>[[NAMA_SURAT]] Nomor: [[NOMOR_SURAT]] Tanggal: [[TANGGAL_SURAT]]</p>',
        ];
    }
}
