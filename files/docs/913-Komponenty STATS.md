{
    "icon": "chart-bar",
    "role": "spellcaster"
}

# counter

```php
<x-shipyard::stats.counter :rank="10" />
```

Symboliczny wyświetlacz wartości. Po najechaniu wyświetla dokładną wartość.

| prop | typ | opis |
| -- | -- | -- |
| ✳️ `rank` | int | wyświetlana liczba |
| `label` | string | dopisuje etykietę do wartości widocznej po najechaniu |
| `style` | **`dots`**/`lines`/`military` | styl wyświetlania |

**Dostępne style:**
- `dots` - pogrupowane kropki
- `lines` - pogrupowane kreski
- `military` - pagon w stylu wojskowym

# tile

```php
<x-shipyard::stats.tile label="Fajności" value="50" />
```

Opisana komórka z wartością. Może też wyświetlać porównanie z innymi wartościami.

| prop | typ | opis |
| -- | -- | -- |
| ✳️ `label` | string | etykieta |
| `value` lub slot | float | wyświetlana wartość |
| `percentageOf` | float | wyświetla też `value` jako procent `percentageOf` |
| `comparedTo` | float | wyświetla też różnicę między `value` a `comparedTo` |

# chart - wykresy 📊

## column

```php
<x-shipyard::stats.chart.column title="Dziwności w skali roku" :data="$data" />
```

Wykres kolumnowy.

| prop | typ | opis |
| -- | -- | -- |
| ✳️ `data` | array | dane do wyświetlenia - patrz niżej |
| `title` | string | tytuł wykresu |
| `subtitle` | string | podtytuł wykresu |
| `icon` | string | ikona przy tytule wykresu |
| `mode` | **`normal`**/`monetary`/`percentage` | tryb wyświetlania danych - patrz niżej |

**Struktura `data`**

Każdy element musi mieć:
- `label` - string - etykieta na dole wykresu
- `value` - float - wartość
- `value_label` - mixed - wartość wyświetlana po najechaniu na słupek

**Tryby wyświetlania danych**
- `normal` - dane wyświetlane as-is
- `monetary` - dane są formatowane walutowo (bez jednostek)
- `percentage` - dane wyświelane w procentach - po najechaniu wyświetla właściwe liczby, chyba że `value_label` jest zdefiniowane
