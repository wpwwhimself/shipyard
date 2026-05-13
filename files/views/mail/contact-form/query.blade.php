@extends("layouts.shipyard.mail")
@section("title", "Wiadomość z formularza kontaktowego")

@section("content")

<ul>
    <li>
        Nadawca:
        {{ implode(" | ", $user_data) }}
    </li>
</ul>

@if ($contents)
{!! \Illuminate\Mail\Markdown::parse($contents) !!}
@endif

@endsection
