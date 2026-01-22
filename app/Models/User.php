<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'active',
        'last_login',
        'last_login_ip',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login' => 'datetime',
            'active' => 'boolean',
        ];
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isContador()
    {
        return $this->role === 'contador';
    }

    public function isAuxiliar()
    {
        return $this->role === 'auxiliar';
    }

    public function isActive()
    {
        return $this->active;
    }

    public function hasRole($roles)
    {
        if (is_string($roles)) {
            $roles = explode(',', $roles);
        }
        
        return in_array($this->role, $roles);
    }

    public function getRoleNameAttribute()
    {
        $roles = [
            'admin' => 'Administrador',
            'contador' => 'Contador',
            'auxiliar' => 'Auxiliar',
            'usuario' => 'Usuario'
        ];

        return $roles[$this->role] ?? 'Usuario';
    }
}
