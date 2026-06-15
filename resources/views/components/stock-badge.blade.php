@props(['status'])

@php
    $map = [
        'tersedia' => ['label' => 'Tersedia', 'classes' => 'bg-success-subtle text-success'],
        'tidak tersedia' => ['label' => 'Tidak Tersedia', 'classes' => 'bg-danger-subtle text-danger'],
        'pre order' => ['label' => 'Pre Order', 'classes' => 'bg-warning-subtle text-warning'],
    ];
    $badge = $map[$status] ?? ['label' => ucfirst($status), 'classes' => 'bg-secondary-subtle text-secondary'];
@endphp

<span {{ $attributes->merge(['class' => 'badge ' . $badge['classes']]) }}>
    {{ $badge['label'] }}
</span>
