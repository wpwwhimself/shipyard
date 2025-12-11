{
    "icon": "dock-window",
    "role": "technical"
}

# 📝 Co to jest modal?

Modal jest wyskakującym okienkiem, pozwalającym **od razu**, bez przechodzenia między stronami, **wykonać pewną czynność**.

Typowym zastosowaniem modala jest tworzenie nowego bytu, np. użytkownika, bez wchodzenia w panel zarządzania użytkownikami.

# ✨ Tworzenie modali

Przed użyciem modala musisz być pewien, że jego definicja jest zapisana [na liście modali](/admin/models/modals). Jeśli jej nie ma, musisz utworzyć nowy.

W edytorze modala możesz zdefiniować jego podstawowe parametry:
- Nazwa - po tej nazwie odwołasz się później do niego,
- Nagłówek - nagłówek na górze okna
- Pola - lista pól, jakie modal powinien wyświetlać. Tworzona tak jak zwykłe inputy.
  - zawartość JSONa _Pozostałe_ będzie bezpośrednio przepisywana na propsy inputa
- Route docelowy - route POST, do którego ma trafić zapytanie.

# 🧑‍💻 Obsługa modali

Wywołanie modala odbywa się za pomocą JSowej funkcji `openModal(name, defaults)`.
Za `name` podaje się nazwę modala.
Jeśli podany jest JSON `defaults`, wartości z niego zostaną przepisane na pola formularza. Jeśli jakiegoś pola brakuje, zostanie ono dodane jako input:hidden.
