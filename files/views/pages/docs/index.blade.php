@extends("layouts.shipyard.docs")
@section("title", "Dokumentacja")

@section("content")

<div class="card">
    <p>Wybierz temat dokumentacji za pomocą przycisków obok.</p>

    @if (auth()->user()->hasRole("technical"))
    <h2>Tworzenie dokumentacji</h2>

    <p>Wpisy w dokumentacji budowane są na podstawie zawartości katalogu <code>docs</code>.</p>
    <p>
        Każdy plik o rozszerzeniu <code>.md</code> będzie wyświetlony na liście obok (posortowane alfabetycznie).
        Nazwa pliku może zaczynać się od <code>???-</code>, gdzie ? stanowi cyfrę. Ten prefiks nie pokaże się w tytule artykułu.
    </p>
    @endif
</div>

@endsection
