{
    "icon": "account",
    "role": "spellcaster"
}

# Modyfikacje panelu użytkownika

Domyślnie na stronie [profilu użytkownika](/profile) wyświetlane są jego podstawowe dane: nazwa użytkownika, jego role w systemie oraz przycisk do edycji danych.

Strona może zostać rozszerzona własnymi komponentami poprzez zdefiniowanie atrybutu modelu `profileComponents`:
```php
public function profileComponents(): Attribute
{
    return Attribute::make(
        get: fn () => [
            view("components.stats.user-stats", [
                "user" => $this,
            ])->render(),
        ],
    );
}
```

Atrybut przewiduje array, w którym każdy element jest osobną sekcją (zaleca się, aby komponent był właśnie `shipyard.app.section`). Komponenty wyświetlane są przed domyślną sekcją.
