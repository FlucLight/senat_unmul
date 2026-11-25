<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class DocumentTemplateService
{
    /**
     * @return array<string, array<string, array<string, mixed>>>
     */
    public function all(): array
    {
        return config('document_templates', []);
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function forType(string $type): array
    {
        return $this->all()[$type] ?? [];
    }

    /**
     * @return array<string, mixed>|null
     */
    public function find(string $type, string $key): ?array
    {
        return $this->forType($type)[$key] ?? null;
    }

    public function defaultKey(string $type): ?string
    {
        foreach ($this->forType($type) as $key => $tpl) {
            if (! empty($tpl['default'])) {
                return $key;
            }
        }

        $keys = array_keys($this->forType($type));

        return $keys[0] ?? null;
    }

    public function render(string $type, string $key, array $data = []): string
    {
        $tpl = $this->find($type, $key);
        if (! $tpl) {
            return '';
        }

        $path = base_path($tpl['path']);
        if (! File::exists($path)) {
            return '';
        }

        $html = File::get($path);

        foreach ($data as $k => $v) {
            $html = str_replace('{{ $' . $k . ' }}', e((string) $v), $html);
            $html = str_replace('{{' . $k . '}}', e((string) $v), $html);
        }

        return $html;
    }
}
