{
    "icon": "account",
    "role": "technical"
}

# Ustawienia

W [ustawieniach systemu](/admin/settings) w sekcji _🟦 Użytkownicy i interakcja_ dostępne są ustawienia związane z użytkownikami.

## Proces odzyskiwania hasła

Jeśli użytkownik zapomni hasła, możle kliknąć przycisk _🟡 Nie pamiętam hasła_. Spowoduje on rozpoczęcie różnych procesów, w zależności od ustawienia.

- **Standardowo** - proces rozpoczyna się od pola, do którego użytkownik podaje swój adres email. Jeśli ten jest poprawny, zostanie na niego wysłany automatyczny mail z linkiem do resetu hasła, gdzie użytkownik samodzielnie nadaje sobie nowe hasło.
- **Administrator nadaje hasło** - w tej wersji użytkownik nie ma żadnej możliwości własnoręcznego resetu hasła. Powinien się on zwrócić do administratora, który powinien ręcznie zmienić hasło w [ustawieniach tego użytkownika](/admin/model/users).

## Test antyspamowy

W niektórych miejscach systemu użytkownik (szczególnie gość) jest proszony o wypełnienie prostego testu pozwalającego odróżnić go od automatów/botów (tzw. test Turinga). Ustawienia mają za zadanie ustalić pytanie i odpowiedź dla tego testu.
