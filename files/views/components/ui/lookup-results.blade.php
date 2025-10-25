@props([
    "data",
    "headings",
    "fieldName",
])

<table>
    <thead>
        <tr>
            @foreach ($headings as $heading)
            <th>{{ $heading }}</th>
            @endforeach
            <th></th>
        </tr>
    </thead>
    <tbody>
        @forelse ($data as $i => $d)
        @if ($i > 20)
        <tr>
            <td colspan="{{ $headings->count() }}">...</td>
        </tr>
        @break
        @endif

        <tr class="interactive"
            onclick="lookupSelect('{{ $fieldName }}', '{{ $d['id'] }}')"
        >
            @foreach ($d as $col)
            <td>{!! $col !!}</td>
            @endforeach
            <td>
                <div class="input-container">
                    <input
                        type="radio"
                        name="{{ $fieldName }}"
                        value="{{ $d['id'] }}"
                    >
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="{{ $headings->count() }}">
                <span class="ghost">Brak wyników</span>
            </td>
        </tr>
        @endforelse
    </tbody>
</table>
