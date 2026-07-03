{
    "icon": "dock-window",
    "role": "spellcaster"
}

# Co to jest modal?

Modal jest wyskakującym okienkiem, pozwalającym **od razu**, bez przechodzenia między stronami, **wykonać pewną czynność**.

Typowym zastosowaniem modala jest tworzenie nowego bytu, np. użytkownika, bez wchodzenia w panel zarządzania użytkownikami.

# Tworzenie modali

Przed użyciem modala musisz być pewien, że jego definicja jest zapisana [na liście modali](/app/Scaffolds/Modal.php). Jeśli jej nie ma, musisz utworzyć nowy.

Lista modali jest określona przez okluczowany array. Klucze to nazwa modala (użyta w jego wywołaniu), a wartości są arrayem o następujących kluczach:
- `heading` - nagłówek na górze okna
- `target_route` - route POST, do którego ma trafić zapytanie
- `summary_route` - route POST do podsumowania, jakie wyświetla się przed zatwierdzeniem formularza
- `fields` - lista pól, jakie modal powinien wyświetlać. Tworzona tak jak zwykłe inputy

## Pola ✏️

Pola składają się z następujących elementów. Podaj je jako okluczowany array:
- `name`,
- `type`:
  - podaj albo typ inputa
  - albo typ `heading`, wtedy zawartość _Etykiety_ będzie traktowana jako treść nagłówka
  - albo typ `paragraph`, wtedy zawartość _Etykiety_ będzie traktowana jako treść akapitu
- `label`
- `icon`
- `required` (bool)
- `extra` - array pól, wstrzykiwany jako dodatkowe propsy komponentu

# Obsługa modali

Wywołanie modala odbywa się za pomocą JSowej funkcji `openModal(name, defaults, overrides, afterAll)`.
Za `name` podaje się nazwę modala.

Jeśli podany jest JSON `defaults`, wartości z niego zostaną przepisane na pola formularza. Jeśli jakiegoś pola brakuje, zostanie ono dodane jako input:hidden.

Jeśli podany jest JSON `overrides`, wartości z niego posłużą do zmodyfikowania pól. Jako opcje można podać opcję dowolnego pola (z wyjątkiem `type` i `name`) lub `hide: true`, żeby usunąć je całkowicie.

Jeśli podana jest funkcja `afterAll`, zostanie ona wykonana po utworzeniu i uzbrojeniu pól. Przydatna do autofocusu pól.
