{
    "icon": "database",
    "role": "spellcaster"
}

# Tworzenie nowego modelu

Żeby utworzyć nowy model, trzeba wykonać `php artisan make:model NazwaModelu --migration`, co stworzy model i migrację na podstawie shipyardowego template'a.

# Konsyderacje bazodanowe

Domyślna migracja tworzy kolumny związane z:
- zarządzaniem widocznością:
  - `visible`
- soft-delete (`Illuminate\Database\Eloquent\SoftDeletes`):
  - `deleted_at`
- userstampami (`Mattiverse\Userstamps\Traits\Userstamps`):
  - `created_by`, `updated_by`, `deleted_by`

Jeśli nie chcesz używać tych cech, możesz pominąć te kolumny w migracji.

# Co składa się na model

## Metadane 💳

Stała `META` przechowuje metadane i podstawowe reguły modelu

### wymagane
- `label`: etykieta - podawane w liczbie mnogiej, np. Projekty
- `icon`
- `description`: opis wyświetlany na listingu modelu
- `role` - role wymagane do przeglądania/edytowania modelu
- `ordering` - indeks na [liście modeli](/admin/models)

### opcjonalne
- `checkOwnerUnless` - (opcj.) - jeśli wypełnione, domyślnie uprawnieni (czyli ci z `role`) mogą przeglądać tylko swoje obiekty (sprawdzane po `created_by`), a uprawnieni z `checkOwnerUnless` widzą wszystkie wpisy
- `listScope` - domyślny scope używany do listingu, jeśli nie ma być to `forAdminList`
- `defaultSort` - domyślne sortowanie listingu (patrz `SORTS`)
- `defaultFltr` - domyslne filtrowanie listingu (patrz `FILTERS`) - 🚧 niezaimplementowane 

## Prezentacja 🔦

Zestaw metod decydujących o wyświetlaniu obiektu na listingu, w edytorze lub gdziekolwiek:
- `__toString()` - standardowy cast do stringa
  - tu można też użyć komponentów, np. ikon albo kolorków
- `optionLabel()` / `option_label` - tak będzie wyświetlać się na listach, np. selektor relacji
  - czysty tekst, tu można wyświetlić też ID
- `displayTitle()` / `display_title` - tak będzie wyświetlać się na tytule kafelka
- `displaySubtitle()` / `display_subtitle`
- `displayPreTitle()` / `display_pre_title` - opcjonalny obiekt na lewo od tytułu, np. dla obrazka produktu
- `displayMiddlePart()` / `display_middle_part` - dane wyświetlane na środku kafelka - zazwyczaj podgląd relacji lub wyświetlanie cech obiektu

### modelAddButton
```php
public static function modelAddButton(): string
{
    return view("components.shipyard.ui.button", [
        "icon" => "plus",
        "label" => "Dodaj",
        "action" => route(...),
        "attributes" => new ComponentAttributeBag([
            "class" => "primary",
        ]),
    ])->render();
}
```

Jeśli chcesz zmienić domyślny przycisk w sidebarze listingu, który prowadzi do pustego formularza obiektu, to zrobisz to tą funkcją.

### modelEditButton
```php
public function modelEditButton(): Attribute
{
    return Attribute::make(
        get: fn () => view("components.shipyard.ui.button", [
            "icon" => "pencil",
            "label" => "Edytuj",
            "action" => route(...),
        ])->render(),
    );
}
```

Jeśli chcesz zmienić przycisk na końcu kafelka obiektu, który prowadzi do edytora obiektu, to zrobisz to tym atrybutem.

## Pola ✏️

Lista pól widocznych podczas edycji obiektu. Wymaga `HasStandardFields`, które dodaje gettery dla pól, relacji itp. Tu też znajdują się definicje fillables.

### domyślne pola

Te pola wyświetlają się zawsze, o ile taka kolumna istnieje dla modelu:
- `id`
- `name`
- `visible` - lista wyboru: nikt/zalogowani/wszyscy
- `order` - indeks wymuszenia kolejności

Utworzenie pola poniżej nadpisuje powyższe.

### edytowalne pola

Stała `FIELDS` zbiera informacje o polach w tablicy. Klucz to nazwa kolumny (snake), a wartość to array o następujących kluczach:

- wymagane
  - `type` - typ inputa: patrz [dokumentację komponentu](/docs/komponenty-ui#dh-1)
  - `label` - etykieta
  - `icon`
- opcjonalne i kontekstowe
  - `hint` - tekst wyświetlany w podpowiedzi - patrz [dokumentacja inputa](/docs/komponenty-ui#dh-1)
  - `required` - t/f
  - `default` - domyślna wartość pola
  - `role` - widoczne tylko dla podanych ról
  - `allowNulls` - t/f
    - zazwyczaj wartość _null_ jest interpretowana przez filtry jako brak filtrowania. Nadaj _true_, żeby null był dozwoloną wartością filtra
  - `autofillFrom`, `columnTypes` - patrz [dokumentacja JSON](/docs/komponenty-ui#dh-1)
  - `characterLimit` - patrz [dokumentacja TEXT](/docs/komponenty-ui#dh-7)
  - `selectData` - patrz [dokumentacja select](/docs/komponenty-ui#dh-11)

## Relacje ⛓️

### CONNECTIONS

Stała `CONNECTIONS` zbiera informacje o relacjach z innymi modelami. Wymaga określonych relacji, definiowanych w tym samym regionie. Dane przechowywane są w tablicy. Klucz to nazwa relacji (camel), a wartość to array o następujących kluczach:

- wymagane
  - `model` - klasa powiązanego modelu
  - `mode`
    - one - odpowiada `belongsTo`
    - many - odpowiada `belongsToMany`
    - many-reverse - odpowiada `hasMany`
- opcjonalne
  - `field_name` - kolumna w obiekcie przechowująca relację, jeśli nie jest to np. `project_id`
  - `field_label` - etykieta relacji w edytorze - domyślnie `META[label]`
  - `readonly` - t/f - blokuje możliwość edycji relacji

### Definicje relacji

Tutaj definiowane są laravelowe relacje.

## Akcje i dodatki 🏃

### ACTIONS

Stała `ACTIONS` przechowuje dane do przycisków akcji - określonych operacji wykonywanych z poziomu listingu lub edytora w pasku bocznym. Dane przechowywane w zwykłej tablicy, każde dziecko ma klucze:

- wymagane
  - `icon`
  - `label` - pop przycisku
  - `show-on`
    - list - wyświetla na listingu
    - edit - wyświetla w edytorze
  - `route`
    - nazwa route'a GET do akcji
    - none - dla przycisku z onclickiem
- opcjonalne
  - `onclick` - działa wtedy, kiedy `route = none`
  - `role`
  - `params` - array - query dla route'a - okluczowany array: `[<param>: <nazwa_atr>]` - domyślnie "id": "id"
  - `dangerous` - t/f - czy przycisk ma mieć danger

### EXTRA_SECTIONS

Stała `EXTRA_SECTIONS` zawiera definicje dodatkowych sekcji wyświetlanych przed listingiem lub na końcu edytora. Dane przechowywane w okluczowanej tablicy - klucz to ID komponentu, a wartość to array o następujących kluczach:
- wymagane
  - `title` - tytuł sekcji
  - `icon`
  - `show-on`
    - list - wyświetla przed listingiem, po filtrach
    - edit - wyświetla jako kolejny kafelek po relacjach
- opcjonalne
  - `role`

Komponent otrzymuje obiekt w postaci propsa `$data`.

## Scope'y 🔭

Definicje scope'ów. Domyślne scope'y pochodzą z `HasStandardScopes`:
- `sortAndFilter`
- `forAdminList`
- `visible`
- `recent`
- `forConnection`
- `classes`

## Sorty i filtry 🧹

### SORTS

Stała `SORTS` zawiera definicje metod sortowania listingu. Dane przechowywane są w okluczowanej tablicy - klucz to nazwa kryterium (wykorzystywana w adresie), a wartość to array:
- `label` - etykieta w filtrach
- `compare-using`
  - field - kolumna w bazie
  - function - atrybut lub helper
- `discr` - nazwa pola lub funkcji

Filtrowanie malejąco osiągane jest przez dodanie minusa przed nazwą sorta, np. `-name`.

### FILTERS

Stała `FILTERS` zawiera kryteria filtrowania listingu w okluczowanej tablicy - klucz to nazwa kryterium (wykorzystywana w adresie), a wartość to array:
- wymagane
  - `label` - etykieta inputa w filtrach
  - `compare-using`
    - field - filtrowanie bazodanowe
    - function - filtrowanie phpowe
  - `discr` - nazwa pola lub funkcji zwracającej testowaną wartość
  - `type` - typ inputa
  - `operator`
    - dla trybu `field`: operator bazodanowy, np. =, like, regexp
    - dla trybu `function`:
      - `=` - miękka równość
      - `~`, `~*` - zawieranie w tekście (case sens./insens.)
- opcjonalne
  - `icon` - jeśli filtr jest w trybie `field`, to wykorzystana zostanie ikona pola
  - `selectData` - dla inputa typu `select`

## Atrybuty i funkcje pomocnicze 🎖️

Definicje atrybutów.

Tu znajdują się definicje castów i appendów

Tu jest też miejsce na helpery (najczęściej statyczne lub prywatne) z dodatkową logiką modelu.

### badges
```php
public function badges(): Attribute
{
    return Attribute::make(
        get: fn () => [
            //
        ],
    );
}
```

Ten atrybut służy do generowania odznak dla obiektu, pozwalających pokazać np. liczbę wpisów dla użytkownika. Elementami zwracanego arraya mają klucze
- logika standardowa
  - `label` - etykieta po najechaniu
  - `icon`
  - `condition` - warunek, po spełnieniu którego wyświetla się badge
  - `class`, `style` - opcjonalne, służą do stylowania
- logika niestandardowa
  - `html` - surowy kod wyświetlanego badge'a

## Funkcje onSave 💾

Funkcje wykonujące się przy zapisie. W poniższych funkcjach `$data` to dane z edytora, które można procesować dalej.

### validateOnSave
```php
public static function validateOnSave($data): array
{
    $res = [
        "result" => true/false,
        "message" => "",
    ];

    // validation...

    return $res;
}
```

Walidacja następująca po zapisaniu formularza w edytorze. Nieudana walidacja wraca do edytora z tostem wyświetlającym `message`.

### autofillOnSave
```php
public static function autofillOnSave(array $data): array
{
    // processing...

    return $data;
}
```

Wypełnianie domyślnych wartości po zapisie formularza w edytorze. Przejmij i zmodyfikuj/dopisz dane do `$data`, a następnie zwróć `$data`.
