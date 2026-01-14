<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'phone',
        'address',
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
        ];
    }

    public function borrowings(): HasMany
    {
        return $this->hasMany(Borrowing::class);
    }

    public function verifiedBorrowings(): HasMany
    {
        return $this->hasMany(Borrowing::class, 'verified_by');
    }

    public function receivedFines(): HasMany
    {
        return $this->hasMany(Fine::class, 'received_by');
    }

    public function isAdminOrStaff(): bool
    {
        return in_array($this->role, ['admin', 'petugas']);
    }

    public function isPeminjam(): bool
    {
        return $this->role === 'peminjam';
    }
}
