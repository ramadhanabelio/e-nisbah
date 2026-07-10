<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Surat;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nik',
        'name',
        'email',
        'password',
        'role',
        'is_active',
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

    public const ROLES = [
        'cs' => 'CS',
        'pinsi_pelnas' => 'PINSI PELNAS',
        'pinbag_operasional' => 'PINBAG Operasional',
        'pincab' => 'PINCAB',
        'admin_pusat' => 'Admin Kantor Pusat',
        'pinbag' => 'PINBAG',
        'pinidiv' => 'PINIDIV',
        'direksi' => 'Direksi',
        'dirut' => 'DIRUT',
    ];

    protected function roleName(): Attribute
    {
        return Attribute::make(
            get: fn() => self::ROLES[$this->role] ?? ucfirst(str_replace('_', ' ', $this->role))
        );
    }

    public function surats()
    {
        return $this->hasMany(Surat::class, 'created_by');
    }
}
