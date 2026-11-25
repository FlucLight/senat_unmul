@props(['class' => ''])

<section {{ $attributes->merge(['class' => 'rounded-md border border-line bg-surface p-6 ' . $class]) }}>
    {{ $slot }}
</section>
