<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['jenis', 'nomor_surat', 'judul', 'status', 'created_by', 'content', 'pdf_path', 'qr_verification_url', 'finalized_at'])]
#[Hidden(['content'])]
class Document extends \Illuminate\Database\Eloquent\Model
{
    use HasFactory;
    public const JENIS = [
        'undangan' => 'Undangan',
        'berita_acara' => 'Berita Acara',
        'surat_pengantar' => 'Surat Pengantar',
        'daftar_hadir' => 'Daftar Hadir',
    ];

    public const STATUS_DRAFT = 'draft';
    public const STATUS_FINAL = 'final';

    protected function casts(): array
    {
        return [
            'content' => 'array',
            'finalized_at' => 'datetime',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isFinal(): bool
    {
        return $this->status === self::STATUS_FINAL;
    }

    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function getJenisLabelAttribute(): string
    {
        return self::JENIS[$this->jenis] ?? $this->jenis;
    }
}