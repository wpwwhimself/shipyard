{
    "icon": "file-swap",
    "role": "spellcaster"
}

# Założenia

Proces rozszerzenia standardowych layoutów strony zakłada oczywiście, że to właśnie one są wykorzystywane w systemie. **Nic nie stoi na przeszkodzie użyć własnego layoutu** do nowej strony. Ale żeby wszystko było jednolite, można posłużyć się istniejącymi.

## Szablony do użycia

- 🟥 `layouts.shipyard.admin` - podstawowy układ z nagłówkiem, treścią i stopką
- 🟨 `layouts.shipyard.minimal` - bez nagłówka; zamiast niego w lewym górnym rogu jest logo aplikacji
  - do zastosowania np. w pełnoekranowych sub-apkach
- 🟩 `layouts.shipyard.mail` - okrojony podstawowy układ bez większośći interaktywnych komponentów
  - przewidziany do standardowych, wystylizowanych maili
- 🟦 `layouts.shipyard.simple` - wyczyszczony z komponentów i z wbudowanymi podstawowymi stylami
  - do zastosowania np. w uproszczonych mailach


# Dostępne rozszerzenia

Idea rozszerzania opiera się na tworzeniu dodatkowych komponentów Blade. Jeśli takowe istnieją, są one automatycznie dodawane do layoutu w określonych miejscach.

Pliki muszą znajdować się w `resources/views/components/layout-extra`.
Niżej wymienione komponenty przyjmują nazwę `*.blade.php`.

Przy nazwach podano również kolorami, czy komponent jest wspierany przez dany layout.

## background 🟥🟨🟩

Tło aplikacji może zostać zmodyfikowane poprzez dodatkowy komponent. W jego treści mogą znajdować się na przykład **wystylowane obrazki latające w tle**.

## footer-extra 🟥🟨🟩

Stopka layoutu (jeśli istnieje) przewiduje miejsce na dodatkowe informacje, np. **dane kontaktowe**. Wyświetlać się one będą wówczas na środku stopki.

Treść komponentu należy zawrzeć w pliku .
