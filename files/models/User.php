<?php

namespace App\Models\Shipyard;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Mail\Shipyard\ResetPasswordLink;
use App\Scaffolds\Role;
use App\Traits\Shipyard\HasStandardScopes;
use App\Traits\Shipyard\HasStandardAttributes;
use App\Traits\Shipyard\HasStandardFields;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\ComponentAttributeBag;
use Laravel\Sanctum\HasApiTokens;
use Mattiverse\Userstamps\Traits\Userstamps;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;

class User extends Authenticatable implements ContractsAuditable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes, Userstamps, Auditable;

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

    protected $fillable = [
        'name',
        'email',
        'password',
        'roles',
    ];

    #region presentation
    public function __toString(): string
    {
        return $this->name;
    }

    public function optionLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->name,
        );
    }

    public function displayTitle(): Attribute
    {
        return Attribute::make(
            get: fn () => view("components.shipyard.app.h", [
                "lvl" => 3,
                "icon" => $this->icon ?? self::META["icon"],
                "attributes" => new ComponentAttributeBag([
                    "role" => "card-title",
                ]),
                "slot" => $this->name,
            ])->render(),
        );
    }

    public function displaySubtitle(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->email,
        );
    }

    public function displayMiddlePart(): Attribute
    {
        return Attribute::make(
            get: fn () => view("components.shipyard.app.model.badges", [
                "badges" => $this->badges,
            ])->render(),
        );
    }
    #endregion

    #region fields
    use HasStandardFields;

    public const FIELDS = [
        "email" => [
            "type" => "email",
            "label" => "Adres email",
            "icon" => "at",
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
        ],
    ];

    public const CONNECTIONS = [
    ];

    public const ACTIONS = [
        [
            "icon" => "key-change",
            "label" => "Zmień hasło",
            "show-on" => "edit",
            "route" => "password.set",
        ],
    ];
    #endregion

    protected $hidden = [
        'password',
        'remember_token',
    ];

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

    #region scopes
    use HasStandardScopes;

    public function scopeForConnection($query)
    {
        return $query->orderBy("name");
    }
    #endregion

    #region attributes
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    use HasStandardAttributes;

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
    #endregion

    #region relations
    #endregion

    #region helpers
    public function hasRole(?string $role, bool $and_is_not_archmage = false): bool
    {
        if (empty($role)) return true;

        $ret = false;
        foreach (explode("|", $role) as $r) {
            $ret = $ret || in_array($r, $this->roles);
        }

        return $ret || (!$and_is_not_archmage && in_array("archmage", $this->roles));
    }
    #endregion

    #region password reset
    public function sendPasswordResetNotification($token): void
    {
        $url = route("password.reset", ["token" => $token]);
        Mail::to($this->email)->send(new ResetPasswordLink($url));
    }
    #endregion
}
