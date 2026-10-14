@extends("shipyard::layouts.admin")
@section("title", "Statystyki systemu")
@section("subtitle", "Administracja")

@section("sidebar")

<div class="card stick-top">
    @foreach ($fields as $section)
    <x-shipyard::ui.button
        :icon="$section['icon'] ?? null"
        :pop="$section['title']"
        class="tertiary"
        action="none"
        onclick="jumpTo('#{{ $section['id'] }}')"
    />
    @endforeach
</div>

@endsection

@section("content")

@php $section = $fields[0] @endphp
<x-shipyard::app.section :id="$section['id']"
    :title="$section['title']"
    :subtitle="'Razem: ' . $section['data']->sum('value')"
    :icon="$section['icon']"
>
    <x-shipyard::stats.chart.bar :data="$section['data']" />
</x-shipyard::app.section>

@php $section = $fields[1] @endphp
<x-shipyard::app.section :id="$section['id']"
    :title="$section['title']"
    :subtitle="$section['subtitle']"
    :icon="$section['icon']"
>
    <x-shipyard::stats.chart.column :data="$section['data']" />
</x-shipyard::app.section>

@endsection
