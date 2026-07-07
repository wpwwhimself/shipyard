{
    "icon": "apple-keyboard-command",
    "role": "technical"
}

# Wstęp

Komendy pozwalają na zarządzanie różnymi rzeczami.

# Dostępne komendy

## Instalacja

### install

```
php artisan shipyard:install {--force}
```

Instaluje wszystko, czego Shipyard potrzebuje:
- kopiuje/linkuje pliki
- uruchamia migracje
- przygotowuje cache styli dla developmentu

Instalacja nie wykonuje się, jeśli nie zmieniła się wersja Shipyarda. Użyj `--force`, żeby zainstalować mimo to.

### uninstall

```
php artisan shipyard:uninstall {--soft}
```

Usuwa pliki Shipyarda.

Domyślnie usuwa konfiguracje (`ShipyardTheme` i pliki `config`). Użyj `--soft`, żeby temu zapobiec.

## Sprzątanie - prybar

```
php artisan shipyard:prybar {mode}
```

Wykonuje operację, jaką normalnie powinna wykonać standardowa instalacja, ale z jakiegoś powodu się nie udało. Dostępne tryby:

| tryb | opis |
| -- | -- |
| `copy_roles` | Przenosi role z `role_user` do `users` oraz `nav_item_role` do `nav_items` - konsekwencja przeniesienia ról do scaffoldów |

## Development

### cache-theme

```
php artisan shipyard:cache-theme
```

Dla środowiska testowego przygotowuje pliki cache dla styli.

### what-now

```
php artisan shipyard:what-now
```

Wyświetla wskazówki po instalacji Shipyarda.
