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
        @foreach ($data as $d)
        <tr>
            @foreach ($d as $col)
            <td>{{ $col }}</td>
            @endforeach
            <td>
                <x-shipyard.ui.input
                    type="radio"
                    :name="fieldName"
                    :value="$d->id"
                />
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
