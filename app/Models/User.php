<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    const ROLE_ADMIN = 'admin';

    const ROLE_MODERATOR = 'moderator';

    const ROLE_CLIENT = 'client';

    const ROLE_ADMINS = [self::ROLE_ADMIN, self::ROLE_MODERATOR];

    const ALL_ROLES = [self::ROLE_ADMIN, self::ROLE_MODERATOR, self::ROLE_CLIENT];

    const STATUS_DELETED = 0;

    const STATUS_ACTIVE = 1;

    const STATUS_NOT_CONFIRMED = 2;

    const STATUS_NOT_FULL_LOGIN = 3;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'first_name',
        'last_name',
        'phone',
        'email',
        'photo_id',
        'status',
        'password',
        'country_id',
        'gender',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function role(): HasOne
    {
        return $this->hasOne(Role::class);
    }

    public function getRoleAttribute()
    {
        $role = Role::query()->where(['user_id' => $this->id])->first();
        if ($role instanceof Role) {
            return $role->role;
        }

        return null;
    }
}
