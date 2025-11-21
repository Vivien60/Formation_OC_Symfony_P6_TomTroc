<?php
declare(strict_types=1);

namespace model\enum;

enum BookAvailabilityStatus : int
{
    case AVAILABLE = 1;
    case UNAVAILABLE = 0;

    public function label() : string
    {
        return match($this) {
            self::AVAILABLE => 'Disponible',
            self::UNAVAILABLE => 'Indisponible'
        };
    }
}