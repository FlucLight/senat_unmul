<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisSurat extends Model
{
    use HasFactory;

    protected $table = 'jenis_surat';

    protected $fillable = [
        'nama_surat',
        'template_surat',
        'logo',
        'ukuran_kertas',
        'margin_atas',
        'margin_bawah',
        'margin_kiri',
        'margin_kanan',
        'ulang_kop',
        'ulang_footer',
        'footer_surat',
    ];

    protected function casts(): array
    {
        return [
            'margin_atas' => 'float',
            'margin_bawah' => 'float',
            'margin_kiri' => 'float',
            'margin_kanan' => 'float',
            'ulang_kop' => 'boolean',
            'ulang_footer' => 'boolean',
        ];
    }

    public function surat(): HasMany
    {
        return $this->hasMany(Surat::class);
    }
}
