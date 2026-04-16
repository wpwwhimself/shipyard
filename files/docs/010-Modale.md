{
    "icon": "dock-window",
    "role": "technical"
}

# 📝 Co to jest modal?

Modal jest wyskakującym okienkiem, pozwalającym **od razu**, bez przechodzenia między stronami, **wykonać pewną czynność**.

Typowym zastosowaniem modala jest tworzenie nowego bytu, np. użytkownika, bez wchodzenia w panel zarządzania użytkownikami.

# ✨ Tworzenie modali

Przed użyciem modala musisz być pewien, że jego definicja jest zapisana [na liście modali](/app/Scaffolds/Modal.php). Jeśli jej nie ma, musisz utworzyć nowy.

W edytorze modala możesz zdefiniować jego podstawowe parametry:
- Nazwa - po tej nazwie odwołasz się później do niego
- Nagłówek - nagłówek na górze okna
- Route docelowy - route POST, do którego ma trafić zapytanie
- Route podsumowania - route POST do podsumowania, jakie wyświetla się przed zatwierdzeniem formularza
- Pola - lista pól, jakie modal powinien wyświetlać. Tworzona tak jak zwykłe inputy

## ☑️ Pola

Pola składają się z następujących elementów. Podaj je jako zwykły array:
- nazwa pola - jako atrybut `name`,
- typ pola:
  - podaj albo typ inputa
  - albo typ `heading`, wtedy zawartość _Etykiety_ będzie traktowana jako treść nagłówka
  - albo typ `paragraph`, wtedy zawartość _Etykiety_ będzie traktowana jako treść akapitu
- etykieta
- ikona
- czy wymagane (bool)
- pozostałe - array pól, wstrzykiwany jako dodatkowe propsy komponentu

# 🧑‍💻 Obsługa modali

Wywołanie modala odbywa się za pomocą JSowej funkcji `openModal(name, defaults, overrides)`.
Za `name` podaje się nazwę modala.
Jeśli podany jest JSON `defaults`, wartości z niego zostaną przepisane na pola formularza. Jeśli jakiegoś pola brakuje, zostanie ono dodane jako input:hidden.
Jeśli podany jest JSON `overrides`, wartości z niego posłużą do zmodyfikowania pól. Jako opcje można podać opcję dowolnego pola (z wyjątkiem `type` i `name`) lub `hide: true`, żeby usunąć je całkowicie.
