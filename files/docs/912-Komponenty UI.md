{
    "icon": "cursor-text",
    "role": "spellcaster"
}

# input

Każde pole posiada:
- `name` - wypełnia nazwę i ID
- `label` - etykieta
- `hint` - podpowiedź z tooltipem
- `icon`
- `disabled`
- `type` - patrz poniżej ⬇️

Każdy typ może być poprzedzony `dummy-` (np. `dummy-select`), co wyświetla jego wartość jako span bez interaktywności

Dostępne typy pola i ich dodatkowe opcje:

## ✏️ text

Standardowe pole tekstowe.

### number, url, datetime-local, ...

Inne HTMLowe typy inputa.

### 🔍 lookup

Pole pozwalające na wyszukiwanie informacji z zewnątrz, np. po odpytaniu API.
- `selectData`:
  - `dataRoute` lub `dataUrl` - route lub adres GET do pobierania informacji
  - `dataParams` - opcjonalne dodatkowe parametry zapytania

Zapytanie lookup zawiera co najmniej parametr `query` i powinno zwracać komponent z wynikami, jak np. `ui.lookup-results`. Ten posiada parametry:
- `data` - tablica z danymi:
  - `id`
  - pozostałe pola
- `headings` - tablica z nagłówkami danych (włącznie z ID)
- `fieldName` - nazwa pola, które przyjmuje ID z lookupa jako wartość

### 📂 url-storage

Wybór pliku z repozytorium.

### 🖼️ icon

Wybór ikony z jej podglądem.

## 📝 TEXT

Duże pole tekstowe.
- `characterLimit` - maksymalna liczba znaków w polu

### 🎼 ABC

Pole do wprowadzania zapisu muzycznego. Korzysta z formatu ABC. Dokumentacja dostępna jest [tutaj](https://abcnotation.com/wiki/abc:standard:v2.1).

### 👾 HTML

Edytor WYSIWYG.

## 🧱 JSON

Tabela
- `autofillFrom` - tablica: [nazwa route'a, nazwa modelu] - //??
- `columnTypes` - tablica z typami kolumn: [`nagłówek` => `typ kolumny`]

## 👈 select, select-multiple

Wybór z listy
- `selectData`:
  - `options`
    - jednowymiarowa tablica z opcjami: [`label`, `value`]
    - dwuwymiarowa tablica: [etykieta grupy => [...opcje grupy j/w]]
  - `optionsFromScope` - auto-generująca lista opcji: bezkluczowa tablica:
    1. klasa modelu
    2. scope
    3. indeks etykiety (dom. `option_label`)
    4. indeks wartości (dom. `id`)
    5. opcjonalne argumenty scope'a
  - `optionsFromStatic` - auto-generująca lista opcji: bezkluczowa tablica:
    1. klasa modelu
    2. funkcja statyczna
    3. indeks etykiety (dom. `label`)
    4. indeks wartości (dom. `value`)
    5. opcjonalne argumenty funkcji
  - `optionsFromConst` - auto-generująca lista opcji: bezkluczowa tablica:
    1. klasa modelu
    2. stała
    3. indeks etykiety (dom. `label`)
    4. indeks wartości (dom. `value`)
  - `radio` - t/f - czy ma wyświetlać jako radio
  - `emptyOption`
    - tekst - nazwa pustej opcji
    - `true` - "brak"
    - `false` (dom.)

## ✅ checkbox

Checkbox.
