<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'username', 'email', 'password', 'role', 'is_banned'];

    // Satu user dapat memiliki banyak kartu pribadi.
    public function cards()
    {
        return $this->hasMany(Card::class);
    }

    // Relasi untuk request yang dikirim user ini.
    public function sentFriendships()
    {
        return $this->hasMany(Friendship::class, 'requester_id');
    }

    // Relasi untuk request yang diterima user ini.
    public function receivedFriendships()
    {
        return $this->hasMany(Friendship::class, 'addressee_id');
    }

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
            'is_banned' => 'boolean',
        ];
    }
}
