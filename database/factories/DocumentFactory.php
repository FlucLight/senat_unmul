<?php

namespace Database\Factories;

use App\Models\Document;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends Factory<Document>
 */
class DocumentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'jenis' => Arr::random(array_keys(Document::JENIS)),
            'judul' => ucfirst(fake()->words(4, true)),
            'nomor_surat' => null,
            'status' => Document::STATUS_DRAFT,
            'created_by' => User::factory(),
            'content' => null,
        ];
    }

    public function final(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Document::STATUS_FINAL,
            'nomor_surat' => '001/Und/Senat-FT/'.fake()->randomNumber(2).'/2026',
            'finalized_at' => now(),
        ]);
    }
}