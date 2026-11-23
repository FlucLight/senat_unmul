<?php

namespace Database\Factories;

use App\Models\JenisSurat;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<JenisSurat>
 */
class JenisSuratFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $defaults = config('senatitor.margins');

        return [
            'nama_surat' => fake()->unique()->sentence(3),
            'template_surat' => config('senatitor.default_template'),
            'logo' => null,
            'ukuran_kertas' => 'a4',
            'margin_atas' => $defaults['top'],
            'margin_bawah' => $defaults['bottom'],
            'margin_kiri' => $defaults['left'],
            'margin_kanan' => $defaults['right'],
            'ulang_kop' => true,
            'ulang_footer' => false,
            'footer_surat' => null,
        ];
    }
}
