<?php

namespace App\Enums;

enum UserRoleEnum: string
{
    case ADMIN = 'admin';
    case MENTOR = 'mentor';
    case INTERN = 'intern';

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_map(
            static fn (self $role): string => $role->value,
            self::cases(),
        );
    }

    public function label(): string
    {
        return ucfirst($this->value);
    }

    public function dashboardRouteName(): string
    {
        return match ($this) {
            self::ADMIN => 'dashboard.admin',
            self::MENTOR => 'dashboard.mentor',
            self::INTERN => 'dashboard.intern',
        };
    }
}
