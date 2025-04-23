<?php

namespace App\Models\Shipyard;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\Shipyard\CanBeStringified;
use App\Traits\Shipyard\HasStandardScopes;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, CanBeStringified, HasApiTokens, SoftDeletes;

    public const META = [
        "label" => "Użytkownicy",
        "icon" => "account",
        "description" => "Lista użytkowników systemu. Każdy z wymienionych może otrzymać role, które nadają mu uprawnienia do korzystania z konkretnych funkcjonalności.",
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'company_data',
    ];

    public const FIELDS = [
        "email" => [
            "type" => "email",
            "label" => "Adres email",
            "icon" => "email",
        ],
        "phone" => [
            "type" => "tel",
            "label" => "Numer telefonu",
            "icon" => "phone",
        ],
        "company_data" => [
            "type" => "JSON",
            "column-types" => [
                "Pole" => "text",
                "Wartość" => "text",
            ],
            "label" => "Dane firmy",
            "icon" => "domain",
            "role" => "administrator|course-manager",
        ],
    ];

    public const CONNECTIONS = [
        "roles" => [
            "model" => Role::class,
            "mode" => "many",
            "role" => "technical",
        ],
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'company_data' => 'collection',
        ];
    }

    #region scopes
    use HasStandardScopes;

    public function scopeMailableAdmins($query, ?string $role = null)
    {
        $query = $query->whereHas("roles", fn ($q) => $q->where("name", "administrator"));
        if ($role) {
            $query = $query->whereHas("roles", fn ($q) => $q->where("name", $role));
        }
        $query = $query->where("email", "NOT REGEXP", "\.test$");

        return $query;
    }
    #endregion

    #region attributes
    public function badges(): Attribute
    {
        return Attribute::make(
            get: fn () => [
                [
                    "label" => "Administrator",
                    "icon" => "wizard-hat",
                    "show" => $this->roles->contains(Role::find("administrator")),
                ],
            ],
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
    public static function hasRole(?string $role, bool $and_is_not_super = false): bool
    {
        if (empty($role)) return true;

        $ret = false;
        foreach (explode("|", $role) as $r) {
            $ret = $ret || Auth::user()->roles->contains(Role::find($r));
        }

        return $ret || (!$and_is_not_super && Auth::user()->roles->contains(Role::find("super")));
    }
    #endregion

    #region password reset
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
    #endregion
}
