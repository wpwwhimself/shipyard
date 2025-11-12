@extends("layouts.shipyard.mail")
@section("title", "Zgłoszenie błędu")

@section("content")

<ul>
    <li>
        Kto:
        @if ($user)
        <a href="{{ route('admin.model.edit', ['model' => 'users', 'id' => $user->id]) }}">{{ $user }}</a>
        @endif

        @if ($user_email)
        pod adresem <a href="mailto:{{ $user_email }}">{{ $user_email }}</a>
        @endif
    </li>
    <li>Gdzie: <a href="{{ $url }}">{{ $url }}</a></li>
    <li>Kiedy: {{ now() }}</li>
</ul>

@if ($actions_description)
<code>{{ $actions_description }}</code>
@endif

@endsection
