<?php

namespace App\Models\Shipyard;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Mail\Shipyard\ResetPasswordLink;
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

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes, Userstamps;

    public const META = [
        "label" => "Użytkownicy",
        "icon" => "account",
        "description" => "Lista użytkowników systemu. Każdy z wymienionych może otrzymać role, które nadają mu uprawnienia do korzystania z konkretnych funkcjonalności.",
        "role" => "",
        "uneditable" => [
            1,
        ],
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
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
    ];

    public const CONNECTIONS = [
        "roles" => [
            "model" => Role::class,
            "mode" => "many",
            "role" => "technical",
        ],
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

    public function badges(): Attribute
    {
        return Attribute::make(
            get: fn () => collect($this->roles)
                ->map(fn ($r) => [
                    "label" => "$r->name: $r->description",
                    "icon" => $r->icon,
                ]),
        );
    }
    #endregion

    #region relations
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
    #endregion

    #region helpers
    public function hasRole(?string $role, bool $and_is_not_archmage = false): bool
    {
        if (empty($role)) return true;

        $ret = false;
        foreach (explode("|", $role) as $r) {
            $ret = $ret || $this->roles->contains(Role::find($r));
        }

        return $ret || (!$and_is_not_archmage && $this->roles->contains(Role::find("archmage")));
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
