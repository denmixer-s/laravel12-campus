<?php

namespace App\Enums;

enum SliderShow: string
{
    case HOME = 'home';
    case FRONTEND = 'frontend';
    case BOTH = 'both';

    public function label(): string
    {
        return match($this) {
            self::HOME => 'Home Page Only',
            self::FRONTEND => 'Frontend Only',
            self::BOTH => 'Both Home & Frontend',
        };
    }

    public function badgeColor(): string
    {
        return match($this) {
            self::HOME => 'bg-blue-100 text-blue-800',
            self::FRONTEND => 'bg-green-100 text-green-800',
            self::BOTH => 'bg-purple-100 text-purple-800',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn($case) => [$case->value => $case->label()])
            ->toArray();
    }
}