# ⚓ Shipyard: where WPWW's projects set sail ⚓

Soon I'll write more here but for now know that this project is a framework for my own web apps.

## How to start?

1. Add repository to Composer and require it:
```json
{
    ...
    "repositories": {
        "shipyard": {
            "type": "vcs",
            "url": "https://github.com/wpwwhimself/shipyard.git"
        }
    },
    "require": {
        ...
        "wpwwhimself/shipyard": "dev-main"
    },
    ...
}
```
2. Install package:
```bash
composer update
```
3. Install files:
```bash
php artisan shipyard:install
```
