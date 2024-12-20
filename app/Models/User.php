<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasTenants
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_superman' => 'boolean',
        ];
    }

    public function jamaah(): BelongsToMany
    {
        return $this->belongsToMany(Jamaah::class);
    }

    public function getTenants(Panel $panel): Collection
    {
        return $this->jamaah;
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return $this->jamaah()->whereKey($tenant)->exists();
    }



    protected static function booted(): void
    {
        // static::creating(function (User $user) {
        //     // $user->assignRole("admin");
        // });
    }
}
