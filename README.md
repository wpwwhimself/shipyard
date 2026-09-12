![Shipyard banner](/meta/full_banner.svg)

This project is a framework for my own web apps.

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
            "url": "../shipyard",
            "options": {
                "symlink": true
            }
        }
    },
    ...
}
```
4. Add Composer env variables to load shipyard locally and install package:
```
composer require wpwwhimself/shipyard:dev-main
```

## Miscellaneous

### Laravel Nightwatch - app tracking

Shipyard comes with Laravel Nightwatch for tracking traffic.
In order to enable it, add the following to your app's `.env`:
```conf
NIGHTWATCH_TOKEN=...
NIGHTWATCH_INGEST_URI=127.0.0.1:2047 # unique for every app on your server
NIGHTWATCH_REQUEST_SAMPLE_RATE=0.1
```

To run the Nightwatch agent you need to set it up to run in the background. Using `systemctl`:
1. create a template `/etc/systemd/system/laravel-nightwatch@.service`:
    ```conf
    [Unit]
    Description=Laravel Nightwatch Agent for %I
    After=network.target
    
    [Service]
    User=www-data
    Group=www-data
    Restart=always
    RestartSec=5
    WorkingDir=/path/to/your/app/%I
    ExecStart=/usr/bin/php /path/to/your/app/%I/artisan nightwatch:agent
    StandardOutput=append:/path/to/your/app/%I/storage/logs/nightwatch-service.log
    StandardError=inherit

    [Install]
    WantedBy=multi-user.target
    ```
2. reload daemon: `sudo systemctl daemon-reload`
3. `sudo systemctl enable laravel-nightwatch@your-app`
4. `sudo systemctl start laravel-nightwatch@your-app.service`
5. verify it works in your app directory: `php artisan nightwatch:status`
