<?php

namespace App\Services;

use DOMDocument;
use DOMElement;
use DOMNode;

class HtmlSanitizer
{
    /** @var list<string> */
    public const ALLOWED_TOKENS = [
        'nomor_surat',
        'tanggal_surat',
        'perihal',
        'kode_verifikasi',
        'qr_verifikasi',
    ];

    /** @var list<string> */
    private const ALLOWED_TAGS = [
        'p', 'br', 'strong', 'b', 'em', 'i', 'u', 's', 'sub', 'sup', 'span', 'div',
        'h1', 'h2', 'h3', 'h4', 'ul', 'ol', 'li', 'blockquote', 'hr',
        'table', 'caption', 'colgroup', 'col', 'thead', 'tbody', 'tfoot', 'tr', 'th', 'td',
    ];

    /** @var list<string> */
    private const ALLOWED_CLASSES = ['ph', 'page-break'];

    /** @var list<string> */
    private const GLOBAL_ATTRS = ['class', 'style'];

    /** @var array<string, list<string>> */
    private const TAG_ATTRS = [
        'span' => ['class', 'style', 'data-ph', 'contenteditable'],
        'div' => ['class', 'style'],
        'table' => ['class', 'style', 'data-table'],
        'th' => ['class', 'style', 'colspan', 'rowspan'],
        'td' => ['class', 'style', 'colspan', 'rowspan'],
        'col' => ['class', 'style', 'span'],
        'colgroup' => ['class', 'style'],
    ];

    /** @var list<string> */
    private const ALLOWED_CSS_PROPERTIES = [
        'text-align', 'width', 'height', 'vertical-align',
        'border', 'border-top', 'border-right', 'border-bottom', 'border-left',
        'border-width', 'border-style', 'border-color',
        'border-collapse', 'padding', 'padding-top', 'padding-right', 'padding-bottom', 'padding-left',
        'margin-left', 'font-weight', 'font-style', 'text-decoration', 'font-size',
    ];

    public function sanitize(?string $html): string
    {
        $html = trim((string) $html);
        if ($html === '') {
            return '';
        }

        $html = $this->purify($html);
        $html = $this->sanitizeDom($html);

        return trim($html);
    }

    private function purify(string $html): string
    {
        $config = \HTMLPurifier_Config::createDefault();
        $config->set('Core.Encoding', 'UTF-8');
        $config->set('Cache.SerializerPath', storage_path('app/htmlpurifier'));
        $config->set('HTML.Doctype', 'HTML 4.01 Transitional');
        $config->set('HTML.AllowedElements', implode(',', self::ALLOWED_TAGS));
        $config->set('HTML.AllowedAttributes', [
            '*.class', '*.style',
            'span.contenteditable', 'span.data-ph',
            'table.data-table',
            'td.colspan', 'td.rowspan', 'th.colspan', 'th.rowspan', 'col.span',
        ]);
        $config->set('CSS.AllowedProperties', self::ALLOWED_CSS_PROPERTIES);
        $config->set('Attr.AllowedFrameTargets', []);
        $config->set('AutoFormat.RemoveEmpty', false);
        $config->set('AutoFormat.RemoveSpansWithoutAttributes', false);

        $def = $config->getHTMLDefinition(true);
        $def->addAttribute('span', 'data-ph', 'Text');
        $def->addAttribute('table', 'data-table', 'Text');
        $def->addAttribute('span', 'contenteditable', 'Enum#false,true');

        $purifier = new \HTMLPurifier($config);

        return $purifier->purify($html);
    }

    private function sanitizeDom(string $html): string
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $previous = libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="utf-8" ?><div id="__root">' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $root = $dom->getElementById('__root');
        if (! $root instanceof DOMElement) {
            return '';
        }

        $this->walk($root);

        $out = '';
        foreach (iterator_to_array($root->childNodes) as $child) {
            $out .= $dom->saveHTML($child);
        }

        return $out;
    }

    private function walk(DOMElement $root): void
    {
        $nodes = [];
        foreach ($root->getElementsByTagName('*') as $node) {
            $nodes[] = $node;
        }

        foreach ($nodes as $node) {
            if (! $node instanceof DOMElement) {
                continue;
            }
            if (! $node->parentNode) {
                continue;
            }

            $tag = strtolower($node->tagName);

            if (! in_array($tag, self::ALLOWED_TAGS, true)) {
                $this->unwrap($node);
                continue;
            }

            $this->sanitizeAttributes($node, $tag);
        }
    }

    private function unwrap(DOMElement $node): void
    {
        $parent = $node->parentNode;
        if (! $parent) {
            return;
        }
        while ($node->firstChild) {
            $parent->insertBefore($node->firstChild, $node);
        }
        $parent->removeChild($node);
    }

    private function sanitizeAttributes(DOMElement $node, string $tag): void
    {
        $allowed = self::TAG_ATTRS[$tag] ?? self::GLOBAL_ATTRS;
        $toRemove = [];

        foreach ($node->attributes as $attr) {
            $name = strtolower($attr->nodeName);
            $value = $attr->nodeValue ?? '';

            if (str_starts_with($name, 'on')) {
                $toRemove[] = $name;
                continue;
            }
            if (! in_array($name, $allowed, true)) {
                $toRemove[] = $name;
                continue;
            }
            if (str_contains(strtolower($value), 'javascript:')) {
                $toRemove[] = $name;
                continue;
            }

            if ($name === 'class') {
                $clean = $this->sanitizeClasses($value);
                if ($clean === '') {
                    $toRemove[] = $name;
                } else {
                    $node->setAttribute('class', $clean);
                }
                continue;
            }

            if ($name === 'style') {
                $clean = $this->sanitizeStyle($value);
                if ($clean === '') {
                    $toRemove[] = $name;
                } else {
                    $node->setAttribute('style', $clean);
                }
                continue;
            }

            if ($name === 'data-ph') {
                if (! in_array($value, self::ALLOWED_TOKENS, true)) {
                    $toRemove[] = $name;
                    $toRemove[] = 'contenteditable';
                }
                continue;
            }

            if ($name === 'data-table') {
                if ($value !== 'peserta') {
                    $toRemove[] = $name;
                }
                continue;
            }

            if ($name === 'contenteditable') {
                $ph = $node->getAttribute('data-ph');
                if ($ph === '' || ! in_array($ph, self::ALLOWED_TOKENS, true) || $value !== 'false') {
                    $toRemove[] = $name;
                }
                continue;
            }

            if (in_array($name, ['colspan', 'rowspan'], true)) {
                if (! ctype_digit($value) || (int) $value < 1) {
                    $toRemove[] = $name;
                }
            }
        }

        foreach (array_unique($toRemove) as $name) {
            if ($node->hasAttribute($name)) {
                $node->removeAttribute($name);
            }
        }

        // If it's a placeholder span, enforce canonical attributes.
        $ph = $node->getAttribute('data-ph');
        if ($tag === 'span' && $ph !== '' && in_array($ph, self::ALLOWED_TOKENS, true)) {
            $node->setAttribute('contenteditable', 'false');
            $classes = $this->sanitizeClasses($node->getAttribute('class') . ' ph');
            $node->setAttribute('class', $classes);
        }
    }

    private function sanitizeClasses(string $value): string
    {
        $classes = preg_split('/\s+/', trim($value)) ?: [];
        $classes = array_values(array_unique(array_filter($classes, fn ($c) => in_array($c, self::ALLOWED_CLASSES, true))));

        return implode(' ', $classes);
    }

    private function sanitizeStyle(string $value): string
    {
        $value = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        if (str_contains(strtolower($value), 'javascript:') || str_contains($value, 'expression')) {
            return '';
        }

        $parts = array_filter(array_map('trim', explode(';', $value)));
        $kept = [];
        foreach ($parts as $part) {
            [$prop, $val] = array_pad(explode(':', $part, 2), 2, null);
            $prop = strtolower(trim((string) $prop));
            $val = trim((string) $val);
            if ($prop === '' || $val === '') {
                continue;
            }
            if (str_contains(strtolower($val), 'javascript:') || str_contains(strtolower($val), 'url(')) {
                continue;
            }
            if (! $this->isAllowedCssProperty($prop)) {
                continue;
            }
            $kept[] = $prop . ': ' . $val;
        }

        return implode('; ', $kept);
    }

    private function isAllowedCssProperty(string $prop): bool
    {
        if (in_array($prop, self::ALLOWED_CSS_PROPERTIES, true)) {
            return true;
        }

        foreach (self::ALLOWED_CSS_PROPERTIES as $allowed) {
            if (str_ends_with($allowed, '*')) {
                $prefix = substr($allowed, 0, -1);
                if (str_starts_with($prop, $prefix)) {
                    return true;
                }
            }
        }

        // allow border* and padding* as specified
        return str_starts_with($prop, 'border') || str_starts_with($prop, 'padding');
    }
}
