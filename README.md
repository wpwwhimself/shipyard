# ⚓ Shipyard: where WPWW's projects set sail ⚓

Soon I'll write more here but for now know that this project is a framework for my own web apps.

## Concepts

This template aims to create a starting point (and a joint upgrade environment, IYKWIM) for all projects made _usually_ for environments without luxury of `npm` – like on a shared hosting. That's why it covers a specific scenario:
- no NPM reliance
  - no asset bundling (Vite)
  - no big frontend frameworks (React, Vue)

## How to start?

Shipyard requires _Laravel_ installation.

1. Add Composer settings:
```json
{
    ...
    // appends Shipyard repository
    "repositories": {
        "shipyard": {
            "type": "vcs",
            "url": "https://github.com/wpwwhimself/shipyard.git"
        }
    },
    // refresh Shipyard after updates
    "scripts": {
        ...
        "post-update-cmd": [
            ...
            "@php artisan shipyard:install"
        ],
    },
    ...
}
```
1. Fill out important fields in `.env`, mainly:
   - `DB_??` - required for migrations,
   - `MAIL_FROM_ADDRESS` - required for archmage user migration,

2. Install package:
```
composer require wpwwhimself/shipyard:dev-main
```

## Dev mode - load Shipyard from a directory next door

1. In a joint folder, clone this repository into `shipyard` subfolder
2. Install Laravel app in a `app` subfolder
3. Add Composer settings
4. Add Composer env variables to load shipyard locally and install package:
```
composer config repositories.shipyard path ../shipyard
composer require wpwwhimself/shipyard:dev-main
```
