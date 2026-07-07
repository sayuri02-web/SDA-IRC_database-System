<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case CertificateManager = 'certificate_manager';
    case WebsiteManager = 'website_manager';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Admin',
            self::CertificateManager => 'Certificate Manager',
            self::WebsiteManager => 'Website Manager',
        };
    }
}
