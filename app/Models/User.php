<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name', 'cpf', 'email', 'password', 'role', 'birth_date', 'cep', 'street', 'number', 'city', 'state'
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'birth_date' => 'date',
    ];

    /**
     * Obter o identificador JWT para o usuário.
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Retornar claims personalizados para o JWT.
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Verifica se o usuário tem papel de administrador.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function employees()
    {
        return $this->hasMany(User::class, 'manager_id');
    }
}
