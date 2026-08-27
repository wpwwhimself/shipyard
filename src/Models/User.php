<?php

namespace Wpwwhimself\Shipyard\Models;

use Wpwwhimself\Shipyard\Mail\ResetPasswordLink;
use App\Scaffolds\Role;
use Wpwwhimself\Shipyard\Traits\HasStandardScopes;
use Wpwwhimself\Shipyard\Traits\HasStandardAttributes;
use Wpwwhimself\Shipyard\Traits\HasStandardFields;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\ComponentAttributeBag;
use Laravel\Sanctum\HasApiTokens;
use Mattiverse\Userstamps\Traits\Userstamps;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;

class User extends Authenticatable implements ContractsAuditable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    public const META = [
        "label" => "Użytkownicy",
        "icon" => "account",
        "description" => "Lista użytkowników systemu. Każdy z wymienionych może otrzymać role, które nadają mu uprawnienia do korzystania z konkretnych funkcjonalności.",
        "role" => "",
        "uneditable" => [
            "archmage",
        ],
        "uneditableField" => "name",
    ];

    use HasStandardFields, HasStandardScopes, HasStandardAttributes;
    use SoftDeletes, Userstamps, Auditable;

    #region presentation
    public function __toString(): string
    {
        return $this->display_name ?? $this->name;
    }

    public function optionLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => $this,
        );
    }

    public function displayTitle(): Attribute
    {
        return Attribute::make(
            get: fn () => view("shipyard::components.app.h", [
                "lvl" => 3,
                "icon" => $this->icon ?? self::META["icon"],
                "attributes" => new ComponentAttributeBag([
                    "role" => "card-title",
                ]),
                "slot" => $this,
            ])->render(),
        );
    }

    public function rawTitle(): Attribute
    {
        return Attribute::make(
            get: fn () => $this,
        );
    }

    public function displaySubtitle(): Attribute
    {
        return Attribute::make(
            get: fn () => implode(" • ", [
                $this->name,
                $this->email,
            ]),
        );
    }

    public function displayMiddlePart(): Attribute
    {
        return Attribute::make(
            get: fn () => view("shipyard::components.app.model.badges", [
                "badges" => $this->badges,
            ])->render(),
        );
    }
    #endregion

    #region fields
    public const FIELDS = [
        "name" => [
            "type" => "text",
            "label" => "Login",
            "icon" => "badge-account-outline",
            "required" => true,
        ],
        "display_name" => [
            "type" => "text",
            "label" => "Nazwa wyświetlana",
            "icon" => "badge-account",
            "hint" => "Nazwa wyświetlana, np. Twoje imię i nazwisko. Jeśli nie podano, Twoje konto wyświetlać będzie Twój login.",
        ],
        "email" => [
            "type" => "email",
            "label" => "Adres email",
            "icon" => "at",
            "required" => true,
        ],
        "roles" => [
            "type" => "select-multiple",
            "label" => "Role",
            "icon" => "key-chain",
            "selectData" => [
                "optionsFromStatic" => [
                    "\\App\\Scaffolds\\Role",
                    "getWithoutArchmage",
                    "option_label",
                    "name",
                ],
            ],
            "role" => "technical",
        ],
    ];
    
    protected $fillable = [
        'name',
        'display_name',
        'email',
        'password',
        'roles',
        "p13n",
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    #endregion

    #region relations
    #endregion
    
    #region actions and extras
    public const ACTIONS = [
        [
            "icon" => "key-change",
            "label" => "Zmień hasło",
            "show-on" => "edit",
            "route" => "password.set",
        ],
    ];
    #endregion

    #region scopes
    public function scopeForConnection($query)
    {
        return $query->orderBy("name");
    }
    #endregion

    #region sorts and filters
    public const SORTS = [
        "name" => [
            "label" => "nazwa użytkownika",
            "compare-using" => "field",
            "discr" => "name",
        ],
        "registration" => [
            "label" => "data rejestracji",
            "compare-using" => "field",
            "discr" => "created_at",
        ],
    ];

    public const FILTERS = [
        "name" => [
            "label" => "Nazwa użytkownika",
            "icon" => "badge-account",
            "compare-using" => "field",
            "discr" => "name",
            "type" => "text",
            "operator" => "regexp",
        ],
        "email" => [
            "label" => "Email",
            "icon" => "at",
            "compare-using" => "field",
            "discr" => "email",
            "type" => "email",
            "operator" => "regexp",
        ],
    ];
    #endregion

    #region attributes and helpers
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'p13n' => "collection",
        ];
    }

    public function roles(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => explode(",", $value ?? ""),
            set: fn ($value) => implode(",", is_array($value) ? $value : [$value]),
        );
    }

    public function badges(): Attribute
    {
        return Attribute::make(
            get: fn () => Role::get($this->roles)
                ->map(fn ($r) => [
                    "label" => "$r[name]: $r[description]",
                    "icon" => $r["icon"],
                ]),
        );
    }

    public function hasRole(?string $role, bool $and_is_not_archmage = false): bool
    {
        if (empty($role)) return true;

        $ret = false;
        foreach (explode("|", $role) as $r) {
            $ret = $ret || in_array($r, $this->roles);
        }

        return $ret || (!$and_is_not_archmage && in_array("archmage", $this->roles));
    }

    public function sendPasswordResetNotification($token): void
    {
        $url = route("password.reset", ["token" => $token]);
        Mail::to($this->email)->send(new ResetPasswordLink($url));
    }
    #endregion

    #region on-saves
    #endregion
}
