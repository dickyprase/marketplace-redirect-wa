@props(['status'])

@php
    $map = [
        'tersedia' => ['label' => 'Tersedia', 'classes' => 'bg-green-100 text-green-800 border border-green-300'],
        'tidak tersedia' => ['label' => 'Tidak Tersedia', 'classes' => 'bg-red-100 text-red-800 border border-red-300'],
        'pre order' => ['label' => 'Pre Order', 'classes' => 'bg-amber-100 text-amber-800 border border-amber-300'],
    ];
    $badge = $map[$status] ?? ['label' => ucfirst($status), 'classes' => 'bg-gray-100 text-gray-700 border border-gray-300'];
@endphp

<span {{ $attributes->merge(['class' => 'inline-block text-xs font-semibold px-2.5 py-1 rounded-full ' . $badge['classes']]) }}>
    {{ $badge['label'] }}
</span>
