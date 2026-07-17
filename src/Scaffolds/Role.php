<?php

namespace Wpwwhimself\Shipyard\Scaffolds;

use Illuminate\Support\Collection;
use Illuminate\View\ComponentAttributeBag;

abstract class Role
{
    public const META = [
        "label" => "Role",
        "icon" => "key-chain",
        "description" => "Lista ról użytkowników w systemie. Rola pozwala użytkownikowi na dostęp do określonych funkcjonalności.",
    ];

    protected static function defaultItems(): array
    {
        return [
            [
                "name" => "archmage",
                "icon" => "wizard-hat",
                "description" => "Może wszystko",
            ],
            [
                "name" => "content-manager",
                "icon" => "multimedia",
                "description" => "Ma dostęp do repozytorium plików oraz stron standardowych",
            ],
            [
                "name" => "technical",
                "icon" => "wrench",
                "description" => "Ma dostęp do parametrów systemu",
            ],
            [
                "name" => "spellcaster",
                "icon" => "magic-staff",
                "description" => "Może rzucać zaklęcia",
            ],
            [
                "name" => "mediator",
                "icon" => "account-voice",
                "description" => "Otrzymuje wiadomości z formularza kontaktowego",
            ],
            // [
            //     "name" => "",
            //     "icon" => "",
            //     "description" => "",
            // ],
        ];
    }
    abstract protected static function items(): array;

    #region getters
    public static function getAll(): Collection
    {
        $all_items = array_merge(self::defaultItems(), static::items());

        self::validateRoles($all_items);

        $ret = collect($all_items)->map(fn ($data) => [
            ...$data,
            "option_label" => "$data[name] — $data[description]",
        ]);

        return $ret;
    }

    public static function getWithoutArchmage(): Collection
    {
        return self::getAll()->filter(fn ($r) => $r["name"] !== "archmage");
    }

    public static function get(array $names): Collection
    {
        return self::getAll()->filter(fn ($r) => in_array($r["name"], $names));
    }

    public static function find(string $name): ?array
    {
        return self::getAll()->firstWhere("name", $name);
    }
    #endregion

    #region validation
    private static function validateRoles(array $roles): void
    {
        $required_keys = ["name", "icon", "description"];
        foreach ($roles as $role) {
            foreach ($required_keys as $key) {
                if (!array_key_exists($key, $role)) {
                    throw new \Exception("⚓ One of your defined roles does not have '$key' key. Fill it out in 'Roles' scaffold.");
                }
            }
        }
    }
    #endregion
}
