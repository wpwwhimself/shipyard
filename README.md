# ⚓ Shipyard: where WPWW's projects set sail ⚓

Soon I'll write more here but for now know that this project is a framework for my own web apps.

## How to start?

Shipyard requires _Laravel_ installation configured with _Breeze_ and _Vue + Inertia_.

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
2. Fill out important fields in `.env`, mainly:
   - `DB_??` - required for migrations,
   - `MAIL_FROM_ADDRESS` - required for archmage user migration,

3. Install package:
```
composer require wpwwhimself/shipyard
```

## Dev mode - load Shipyard from a directory next door

1. In a joint folder, clone this repository into `shipyard` subfolder
2. Install Laravel app in a `app` subfolder
3. Add Composer settings:
```json
{
    ...
    // appends Shipyard repository
    "repositories": {
        "shipyard": {
            "type": "path",
            "url": "../shipyard"
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
4. The rest goes like normal installation

## Resources

- icons reference - [Prime Icons](https://primevue.org/icons/)
