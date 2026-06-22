<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $protected = [
        'id',
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
        ];
    }

    // Middleware Role

    public function isRole(string $role): bool
    {
        return $this->role?->name === $role;
    }

    public function hasRole(array|string $roles): bool
    {
        $roles = (array) $roles;

        return in_array($this->role?->name, $roles);
    }

    public function canAccessRole(array|string $roles): bool
    {
        if ($this->isRole('Superadmin')) {
            return true;
        }

        return $this->hasRole($roles);
    }
    
    // End of Middleware Role

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function consultation()
    {
        return $this->hasMany(Consultation::class, 'doctor_id');
    }

    
}
