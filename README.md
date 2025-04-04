# ⚓ Shipyard: where WPWW's projects set sail ⚓

Soon I'll write more here but for now know that this project is a framework for my own web apps.

## How to start?

1. Add Composer settings:
```json
{
    ...
    "repositories": { // appends Shipyard repository
        "shipyard": {
            "type": "vcs",
            "url": "https://github.com/wpwwhimself/shipyard.git"
        }
    },
    "require": { // adds Shipyard to packages
        ...
        "wpwwhimself/shipyard": "dev-main"
    },
    "scripts": {
        ...
        "post-update-cmd": [ // refresh Shipyard after updates
            ...
            "@php artisan shipyard:install"
        ],
    },
    ...
}
```
2. Install package:
```
composer update
```
