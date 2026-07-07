<?php

namespace App\Models;

use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'username', 'email', 'password', 'role'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    public function isCertificateManager(): bool
    {
        return $this->role === UserRole::CertificateManager;
    }

    public function isWebsiteManager(): bool
    {
        return $this->role === UserRole::WebsiteManager;
    }

    public function hasAccessTo(string $module): bool
    {
        if ($this->isAdmin()) return true;

        return match ($module) {
            'certificates' => $this->isCertificateManager(),
            'website-management' => $this->isWebsiteManager(),
            'admin' => $this->isAdmin(),
            default => false,
        };
    }
}
