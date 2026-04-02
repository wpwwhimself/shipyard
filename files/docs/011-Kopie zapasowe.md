{
    "icon": "backup-restore",
    "role": "technical"
}

# 📦 Kopie zapasowe systemu

Kopie są tworzone codziennie po północy. Kompletna kopia zawiera dane pozwalające na odtworzenie:
- bazy danych
- repozytorium plików

# 🛠️ Konfiguracja

## Katalogi do wykluczenia

Standardowo kopia zapasowa nie pomija żadnych istotnych dla systemu katalogów.
Aby dodać katalogi do wykluczenia, należy dodać zmienną do pliku `.env` zawierającą ścieżki z poziomu roota aplikacji (oddzielone dwukropkami):
```env
SHIPYARD_BACKUP_EXCLUDES=storage/files:storage/test
```
