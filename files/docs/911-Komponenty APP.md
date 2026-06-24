{
    "icon": "wall",
    "role": "spellcaster"
}

# Ogólne

## app-badge 📇

Wyświetla dane o aplikacji: logo, nazwę, prawa autorskie i wesję Shipyarda. Używane zazwyczaj w stopce.

## card 💳

Podstawowy komponent grupujący informacje.
| prop | typ (dom) | opis |
| -- | -- | -- |
| `title` | str |
| `subtitle` | str | wymaga tytułu |
| `icon` | str |
| `titleLvl` | int (3) |
| `innerClass` | str | klasy do przekazania do ciała sekcji |
| `innerStyle` | str | style do przekazania do ciała sekcji |

| slot | opis |
| -- | -- |
| - | ciało sekcji |
| `actions` | przyciski w prawej części nagłówka |

## form 📝

Wrapper formularza HTMLowego.
| slot | opis |
| -- | -- |
| - | ciało formularza |
| `actions` | przyciski na dole formularza |

Zawiera wbudowany tag CSRF i funkcję `submitShipyardForm`.

### submitShipyardForm

Pozwala na zatwierdzenie formularza z dowolnego miejsca. Automatycznie dokłada input `method: save`, wykorzystywany w niektórych formularzach.
| param | typ (dom) | opis |
| -- | -- | -- |
| `method` | str (save) |
| `method_name` | str (method) |

## icon 🖼️

Rysuje ikonę.
| prop | typ (dom) | opis |
| -- | -- | -- |
| `name` | str | nazwa ikony |
| `mode` | "mdi"/"url" (mdi) | tryb działania |
| `data` | str | dane ikony |

Dostępne tryby:
- `url` - przekaż url ikony do `data`
- string - wyszukiwanie ikony z zainstalowanego zestawu. Aktualnie dostępne:
  - MDI (postare/blade-mdi)

## h 👦

Wrapper nagłówka HTMLowego z wbudowaną ikoną.
| prop | typ (dom) | opis |
| -- | -- | -- |
| `lvl` | int (1) | poziom nagłówka |
| `icon` | str |
| `iconMode` | str |
| `iconData` | str |
